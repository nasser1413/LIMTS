        <!-- Include the table builder -->
        <script src="assets/js/wingpad.tablebuilder.js"></script>
        <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />

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
                    $("#content").append(tableBuilder.getHTML());
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
        </script>

        <div class="btn-group btn-group-justified" role="group" style="margin-bottom: 10px">
            <a href="#" class="btn btn-default" role="button">Left</a>
            <a href="#" class="btn btn-default" role="button">Middle</a>

            <div class="btn-group" role="group">
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    Dropdown <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div>
        </div>

        <div id="content"></div>

        <style>
        #sections {
            width: 100%;
            height: 100%;
        }
        </style>
