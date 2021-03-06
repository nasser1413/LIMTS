        <script type="text/javascript">
            var modifiedCapacity = false,
                modifiedCreditHours = false,
                modifiedTeachingLoad = false,
                meetingTimeRegex = /([A-Z]+) *(\d+:\d+[ap])-(\d+:\d+[ap])/,
                rowId = 0,
                rowTemplate,
                editId = getParameterByName("edit"),
                sectionType = 1,
                loadedSection;

            function onFormSubmitted() {
                var section = {};

                if (editId) {
                    section.database_id = editId;
                }

                if (modifiedCreditHours) {
                    section.credit_hours = $("#credit-hours").val();
                }

                if (modifiedTeachingLoad) {
                    section.tl_credits = $("#contact-hours").val();
                }

                if (modifiedCapacity) {
                    section.max_capacity = $("#max-capacity").val();
                }

                section.class = $("#class-selector option:selected").val();
                section.professor = $("#professor-selector option:selected").val();
                section.semester = $("#semester-selector option:selected").val();

                if (!$("#max-capacity").val()) {
                    $("#max-capacity").closest(".form-group").addClass("has-error");
                }

                section.identifier = $("#identifier").val();
                if (!section.identifier) {
                    $("#identifier").closest(".form-group").addClass("has-error");
                }

                section.meeting_type = sectionType;
                if (section.meeting_type == 1) {
                    section.rooms = [];
                    section.meeting_times = [];

                    for (var i = 1; i <= rowId; i++) {
                        var meetingTime = $("#meeting-time" + i);
                        var parent = meetingTime.parents(".form-group");

                        if (!meetingTimeRegex.test(meetingTime.val())) {
                            parent.addClass("has-error");
                        }

                        var room = $("#room-selector" + i + " option:selected");
                        if (!room) {
                            parent.addClass("has-error");
                        }

                        section.meeting_times.push(meetingTime.val());
                        section.rooms.push(room.val());
                    }

                    section.meeting_times = JSON.stringify(section.meeting_times);
                    section.rooms = JSON.stringify(section.rooms);
                }

                if ($(".has-error").length !== 0) {
                    $("#danger-alert")
                        .empty()
                        .append("<strong>Error!</strong> You must fill out the highlighted fields!")
                        .offcanvas("show");
                } else {
                    $.ajax({
                        dataType: "json",
                        url: "creators/create_section.php",
                        data: section,
                        success: function(data) {
                            if (data.response !== "Success") {
                                $("#danger-alert")
                                    .empty()
                                    .append("<strong>Error!</strong> " + data.response)
                                    .offcanvas("show");
                            } else {
                                window.location.href = "?page=calendar" + location.hash;
                            }
                        }
                    });
                }
            }

            function onCapacityChanged() {
                modifiedCapacity = true;
            }

            function onRoomSelected() {
                // TODO: We need to work on this logic!
                var room = $(this).find("option:selected");
                var selectedCapacity = room.attr("capacity");

                var curVal = $("#max-capacity").val();

                if (!modifiedCapacity && ((curVal > selectedCapacity) || !curVal)) {
                    $("#max-capacity").val(selectedCapacity);
                    $("#max-capacity").parents(".form-group").removeClass("has-error");
                }

                if (selectedCapacity) {
                    room
                        .parents(".form-group")
                        .removeClass("has-error");
                }
            }

            function onMeetingTimeChanged() {
                var meetingTime = $(this);
                var parent = meetingTime.closest(".form-group");

                if (meetingTimeRegex.test(meetingTime.val())) {
                    parent.removeClass("has-error");
                } else {
                    parent.addClass("has-error");
                }
            }

            function addRow(room, meetingTime) {
                var row = $(rowTemplate);
                rowId++;

                row.attr("id", "meeting-row" + rowId);

                row.find("#room-selector")
                    .attr("id", "room-selector" + rowId);

                row.find("#meeting-time")
                    .attr("id", "meeting-time" + rowId)
                    .change(onMeetingTimeChanged)
                    .val(meetingTime);

                row.find("#del-button")
                    .attr("id", "del-button" + rowId)
                    .click(removeRow);

                $("#meeting-times").append(row);

                loadRooms(onRoomSelected, {
                    multiselect: false,
                    offset: rowId,
                    done: function() {
                        $(this).find("option").filter(function() {
                            return $(this).html() === room;
                        }).prop("selected", true);

                        onRoomSelected.apply(this);
                    }
                });
            }

            function removeRow() {
                if ($("#meeting-times").find("tr").length > 1) {
                    var row = $("#" + this.id).parents("tr");
                    row.remove();
                } else {
                    $("#warning-alert")
                        .empty()
                        .append("<strong>Warning!</strong> You must have at least one meeting time!")
                        .offcanvas("show");
                }
            }

            function onIdentifierChanged() {
                var identifer = $(this);

                if (identifer.val()) {
                    identifer
                        .parents(".form-group")
                        .removeClass("has-error");
                }
            }

            function onClassSelected() {
                var $selector = $(this),
                    $selected = $selector.find("option:selected");

                ajaxLoadJSON("class", function(i, selectedClass) {
                    if (!modifiedTeachingLoad) {
                        if (selectedClass.tl_credits !== null) {
                            $("#contact-hours").val(selectedClass.tl_credits);
                        } else {
                            $("#contact-hours").val(selectedClass.credithours);
                        }
                    }

                    if (!modifiedCreditHours) {
                        $("#credit-hours").val(selectedClass.credithours);
                    }
                }, {
                    id: $selected.val()
                });
            }

            $(function() {
                rowTemplate = $("#row-template").html();

                ajaxs = [];

                ajaxs.push(loadSelector("semester", undefined, {
                    multiselect: false,
                    precheck: false
                }));

                ajaxs.push(loadSelector("professor", undefined, {
                    multiselect: false,
                    precheck: false
                }));

                ajaxs.push(loadSelector("class", undefined, {
                    multiselect: false,
                    done: (editId ? undefined : onClassSelected),
                    precheck: false
                }));

                if (editId) {
                    $("#form-header").text("Edit Section");

                    ajaxLoadJSON("section", function(i, section) {
                        loadedSection = section;
                        var splitName = loadedSection.name.split("-");
                        var className = splitName[0] + "-" + splitName[1];

                        $.when.apply($, ajaxs).then(function() {
                          $("#professor-selector option").filter(function() {
                              return $(this).html() === loadedSection.professor;
                          }).prop("selected", true);

                          $("#semester-selector option").filter(function() {
                              return $(this).html() === loadedSection.semester;
                          }).prop("selected", true);

                          $("#class-selector option").filter(function() {
                              return $(this).html() === className;
                          }).prop("selected", true);
                        });

                        $("#identifier").val(splitName[2]);

                        if (loadedSection.max_capacity) {
                            $("#max-capacity").val(loadedSection.max_capacity);
                            modifiedCapacity = true;
                        }

                        if (loadedSection.credit_hours != null) {
                            modifiedCreditHours = true;
                            $("#credit-hours").val(loadedSection.credit_hours);
                        }

                        if (loadedSection.tl_credits != null) {
                            modifiedTeachingLoad = true;
                            $("#contact-hours").val(loadedSection.tl_credits);
                        }

                        $.each(loadedSection.meeting_times, function(i, meetingTime) {
                            addRow(loadedSection.rooms[i], meetingTime);
                        });

                        $('#type-group input[value="'+loadedSection.meeting_type+'"]').prop("checked", true).click();
                    }, {
                        id: [editId]
                    });
                } else {
                    addRow();
                }

                $("#add-row").click(addRow);
                $("#add-row").css("cursor", "pointer");

                $("#max-capacity").change(onCapacityChanged);

                $("#identifier").change(onIdentifierChanged);

                $("#class-selector").change(onClassSelected);

                $("#credit-hours").change(function() {
                    modifiedCreditHours = true;
                });

                $("#contact-hours").change(function() {
                    modifiedTeachingLoad = true;
                });

                $('input[type="radio"]').click(function() {
                    var $radio = $(this);
                    sectionType = $radio.val();

                    if (sectionType != 1) {
                        $("#times-group").css("display", "none");

                        $("#times-group")
                            .parents(".form-group").find(".has-error")
                            .removeClass("has-error");
                    } else {
                        $("#times-group").css("display", "block");
                    }
                });
            });
        </script>

        <div class="alert alert-danger alert-fixed-top offcanvas" id="danger-alert">
            <strong>Error!</strong> You must fill out the highlighted fields!
        </div>

        <div class="alert alert-warning alert-fixed-top offcanvas" id="warning-alert">
            <strong>Failure!</strong> No backend has been implemented yet!
        </div>

        <script id="row-template" type="text/x-custom-template">
            <tr id="meeting-row" style="width: 100%">
                <td>
                    <select class="form-control" id="room-selector" name="roomName">
                    </select>
                </td>

                <td>
                    <div class="input-group form-group" style="padding: 0px 0px; margin-bottom: 0px">
                        <input type="text" class="form-control" id="meeting-time" name="meetingTime" placeholder="MWF 9:00a-9:50a">
                        <span class="glyphicon glyphicon-remove input-group-addon" style="cursor: pointer;" id="del-button"></span>
                    </div>
                </td>
            </tr>
        </script>

        <h1 id="form-header">Add Section</h1>
        <form action="javascript:onFormSubmitted()" id="mainForm">
            <div class="form-group">
                <label for="className1">Name:</label>
                <select class="form-control" id="class-selector" name="className" disabled>
                </select>
            </div>
            <div class="form-group">
                <label for="professorName1">Professor:</label>
                <select class="form-control" id="professor-selector" name="professorName" disabled>
                </select>
            </div>
            <div class="form-group">
                <label for="semesterName1">Semester:</label>
                <select class="form-control" id="semester-selector" name="semesterName" disabled>
                </select>
            </div>
            <div class="form-group">
                <label for="max-capacity">Max Capacity:</label>
                <input type="number" class="form-control" id="max-capacity" name="maxCapacity">
                <p class="help-block"><i>If left blank this will be populated based on the selected room(s)</i></p>
            </div>
            <div class="form-group">
                <label for="max-capacity">Credit Hours:</label>
                <input type="number" class="form-control" id="credit-hours" name="creditHours">
                <p class="help-block"><i>If left blank this will be populated based on the selected class</i></p>
            </div>
            <div class="form-group">
                <label for="contact-hours">Teaching Load Credits:</label>
                <input type= "text" class="form-control" id="contact-hours" name="contactHours">
                <p class="help-block"><i>If left blank this will be populated based on the selected class</i></p>
            </div>
            <div class="form-group">
                <label for="classIdentifier1">Identifier:</label>
                <input type="text" class="form-control" id="identifier" placeholder="A" name="classIdentifier">
            </div>
            <div class="form-group">
                <label for="type-group">Meeting Types:</label>
                <div id="type-group">
                    <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" value="1" checked> Normal
                    </label>

                    <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" value="3" disabled> Odd Weeks
                    </label>

                    <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" value="4" disabled> Even Weeks
                    </label>

                    <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" value="5"> Online
                    </label>

                    <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" value="2"> TBA
                    </label>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;" id="times-group">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-4">Room</th>
                            <th class="col-md-8">Meeting Time <span class="glyphicon glyphicon-plus vertical-align" style="float: right;" id="add-row"></span></th>
                        </tr>
                    </thead>
                    <tbody id="meeting-times"></tbody>
                </table>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Submit">
            </div>
        </form>
