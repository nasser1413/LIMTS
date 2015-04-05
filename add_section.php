        <script type="text/javascript">
            var modifiedCapacity = false,
                meetingTimeRegex = /([A-Z]+) *(\d+:\d+[ap])-(\d+:\d+[ap])/,
                rowId = 0,
                rowTemplate;

            function onFormSubmitted() {
                var section = { };

                section.class = $("#class-selector option:selected").val();
                section.professor = $("#professor-selector option:selected").val();
                section.semester = $("#semester-selector option:selected").val();

                section.max_capacity = $("#max-capacity").val();
                if (!section.max_capacity) {
                    $("#max-capacity").closest(".form-group").addClass("has-error");
                }

                section.identifier = $("#identifier").val();
                if (!section.identifier) {
                    $("#identifier").closest(".form-group").addClass("has-error");
                }

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
                    section.rooms.push(room.attr("_id"));
                }
                section.meeting_times = JSON.stringify(section.meeting_times);
                section.rooms = JSON.stringify(section.rooms);

                if ($(".has-error").length !== 0) {
                    $("#danger-alert")
                        .empty()
                        .append("<strong>Error!</strong> You must fill out the highlighted fields!")
                        .offcanvas("show");
                } else {
                    $.ajax({
            		    dataType: "json",
            		    url: "create_section.php",
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
                // TODO: I need to work on this logic :3
                var room = $(this);
                var selectedCapacity = room.find("option:selected").attr("_cap");

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

            function onBuildingSelected() {
                var selector = $(this);
                var offset = selector.attr("offset");
                var selectedBuilding = selector.find("option:selected").val();
                var roomSelector = $("#room-selector" + offset);
                roomSelector.empty();

                $.ajax({
        		    dataType: "json",
        		    url: "get_rooms.php",
                    data: {
                        building: selectedBuilding
                    },
                    success: function(data) {
                        $.each(data, function(i, room) {
                            var id = room.id,
                            nmbr = room.nmbr,
                            capacity = room.cap;
                            roomSelector
                                .append($("<option>", { _id : id, _cap : capacity })
                                .text(nmbr));
                        });

                        if (data.length !== 0) {
                            roomSelector.prop("disabled", false);
                        } else {
                            roomSelector.prop("disabled", true);
                        }

                        roomSelector.change(onRoomSelected);
                        onRoomSelected.apply(roomSelector);
                    }
                });
            }

            function addRow() {
                var row = $(rowTemplate);
                rowId++;

                row.attr("id", "meeting-row" + rowId);
                row.find("#building-selector").attr("id", "building-selector" + rowId);
                row.find("#room-selector").attr("id", "room-selector" + rowId);
                row.find("#meeting-time").attr("id", "meeting-time" + rowId).change(onMeetingTimeChanged);
                row.find("#del-button").attr("id", "del-button" + rowId).click(removeRow);

                $("#meeting-times").append(row);

                loadSelector("building", onBuildingSelected, {
                    multiselect: false,
                    offset: rowId
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

            loadSelector("semester", undefined, {
                multiselect: false
            });
            loadSelector("professor", undefined, {
                multiselect: false
            });
            loadSelector("class", undefined, {
                multiselect: false
            });

            $(function() {
                rowTemplate = $("#row-template").html();

                $("#add-row").click(addRow);
                $("#add-row").css("cursor", "pointer");

                $("#max-capacity").change(onCapacityChanged);

                $("#identifier").change(onIdentifierChanged);

                addRow();
            });
        </script>

        <div class="alert alert-danger alert-fixed-top offcanvas" id="danger-alert">
            <strong>Error!</strong> You must fill out the highlighted fields!
        </div>

        <div class="alert alert-warning alert-fixed-top offcanvas" id="warning-alert">
            <strong>Failure!</strong> No backend has been implemented yet!
        </div>

        <script id="row-template" type="text/x-custom-template">
            <tr id="meeting-row">
                <td class="col-xs-2">
                    <select class="form-control" id="building-selector" name="buildingName" disabled>
                    </select>
                </td>

                <td class="col-xs-2">
                    <select class="form-control" id="room-selector" name="roomName" disabled>
                    </select>
                </td>

                <td class="col-xs-8">
                    <div class="input-group form-group" style="padding: 0px 0px; margin-bottom: 0px">
                        <input type="text" class="form-control" id="meeting-time" name="meetingTime" placeholder="MWF 9:00a-9:50a">
                        <span class="glyphicon glyphicon-remove input-group-addon" style="cursor: pointer;" id="del-button"></span>
                    </div>
                </td>
            </tr>
        </script>

        <h1>Add Section</h1>
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
            <input type="text" class="form-control" id="max-capacity" name="maxCapacity">
            <p class="help-block"><i>If left blank this will be populated based on the selected room(s)</i></p>
            </div>

            <div class="form-group">
            <label for="classIdentifier1">Identifier:</label>
            <input type="text" class="form-control" id="identifier" placeholder="A" name="classIdentifier">
            </div>

            <div class="form-group" style="margin-bottom: 5px;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Building</th>
                            <th>Room</th>
                            <th>Meeting Time</th>
                            <th><span class="glyphicon glyphicon-plus vertical-align" style="float: right;" id="add-row"></span></th>
                        </tr>
                    </thead>

                    <tbody id="meeting-times"></tbody>
                </table>
            </div>

            <div class="form-group">
            <input type="submit" class="btn btn-default" value="Submit">
            </div>
        </form>
