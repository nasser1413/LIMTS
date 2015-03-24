        <!-- Include the table builder -->
        <script src="assets/js/wingpad.tablebuilder.js"></script>
        <script src="assets/js/bootstrap-multiselect.js"></script>
        <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />
        <link type="text/css" rel="stylesheet" href="assets/css/bootstrap-multiselect.css" />

        <script type="text/javascript">
        var calendarView = <?php echo json_encode(!$_GET["table"]) ?>;

        if (calendarView) {
            $(function() {
                $("#content").fullCalendar({
                    theme: true,
                    height: "auto",
                    header: {
                        left: "prev,next today",
                        center: "title",
                        right: "month,agendaWeek,agendaDay"
                    },
                    events: "sections_feed.php",
                    eventClick: function(calEvent, jsEvent, view) {
                        alert("Event: " + calEvent.title);
                    },
                    eventRender: function(event, element) {
                        element.css("cursor", "pointer");
                    },
                    weekends: false
                });
            });
        } else {
            $.ajax({
                dataType: "json",
                url: "table_feed.php",
                success: function(data) {
                    // Instanstiate a new TableBuilder
                    var tableBuilder = new TableBuilder({
                            "id": "sections"
                        });

                    // Start the table"s header
                    tableBuilder.startElement("thead");
                    // Add a Row of class "header_row"
                    tableBuilder.addRow({
                            "class": "header_row"
                        });

                    var headers = data.columns;
                    for (var i = 0; i < headers.length; i++) {
                        tableBuilder.addData(headers[i], {
                                "class": "header_item"
                            });
                    }

                    // End the table"s header
                    tableBuilder.endElement("thead");

                    // Start the table"s body
                    tableBuilder.startElement("tbody");

                    var sections = data.sections;
                    for (var i = 0; i < sections.length; i++) {
                        var section = sections[i];

                        // start a new row (of class "section_row" & id "row_XXXXXXX")
                        tableBuilder.addRow({
                                "class": "section_row",
                                "id": "row_" + section.database_id
                            });

                        for (var key in section) {
                            if (section.hasOwnProperty(key)) {
                                if (key !== "database_id") {
                                    tableBuilder.addData(section[key], {
                                            "class": key
                                        });
                                }
                            }
                        }
                    }

                    // End the table"s body
                    tableBuilder.endElement("tbody");

                    // Finalize the table
                    tableBuilder.finalize();

                    // Replace the placeholder with the HTML from the tablebuilder
                    // $("#sections").replaceWith(tableBuilder.getHTML());
                    $("#content").replaceWith(tableBuilder.getHTML());
                    $("#sections").DataTable({
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 3
                        }],
                        "paging": false
                    });
                }
            });
        }

        function loadSelector(type, handler) {
           var url = "get_" + pluralize(type) + ".php";
           $.ajax({
	            dataType: "json",
	            url: url,
	            success: function(data) {
		            var selector = $("#" + type + "-selector");
		            $.each(data, function(i, object) {
                        var id = object.id,
                            abbr = object.name ? object.name : object.abbr;
                        selector
                            .append($("<option>", { "value" : id, "internal-type" : type })
                            .text(abbr));
                    });

                    if (data.length !== 0) {
                        selector.prop("disabled", false);
                    } else {
                        selector.prop("disabled", true);
                    }

                    selector.multiselect({
                        buttonWidth: '100%',
                        maxHeight: 200,
                        onChange: handler
                    });
	            }
            });
        }

        function loadRooms(handler) {
            $.ajax({
                dataType: "json",
                url: "get_buildings.php",
                success: function(buildings) {
                    var selector = $("#room-selector");
                    $.each(buildings, function(i, building) {
                        $.ajax({
                            dataType: "json",
                            url: "get_rooms.php",
                            data: {
                                building: building.id                            
                            },
                            success: function(rooms) {
                                if (rooms.length === 0) {
                                    return;                                
                                }
                                var optgroup = $("<optgroup label=\"" + building.description + "\"></optgroup>");
                                selector.append(optgroup);

                                $.each(rooms, function(i, room) {
                                    optgroup.append("<option value=\"" + room.id + "\" internal-type=\"room\">" + building.abbr + "-" + room.nmbr + "</option>");
                                });

                                selector.multiselect('rebuild');
                            }
                         });
                    });

                    if (buildings.length !== 0) {
                        selector.prop("disabled", false);
                    } else {
                        selector.prop("disabled", true);
                    }

                    selector.multiselect({
                        buttonWidth: '100%',
                        maxHeight: 200,
                        enableClickableOptGroups: true,
                        onChange: handler
                    });
                }
            });
        }

        loadSelector("professor");
        loadSelector("class");
        loadRooms();

        </script>

        <div class="btn-group btn-group-justified" role="group" style="margin-bottom: 10px">
            <select id="room-selector" multiple="multiple" disabled>
            </select>
            
            <select id="class-selector" multiple="multiple" disabled>
            </select>

            <select id="professor-selector" multiple="multiple" disabled>
            </select>
        </div>

        <div id="content"></div>

        <style>
        #sections {
            width: 100%;
            height: 100%;
        }
        </style>
