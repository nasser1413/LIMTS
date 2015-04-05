var lastEventFilters = {};

function loadRooms(onChange, onDone) {
    $.ajax({
        dataType: "json",
        url: "get_buildings.php",
        success: function(buildings) {
            var selector = $("#room-selector");
            var results = [];
            $.each(buildings, function(i, building) {
                var async = $.ajax({
                    dataType: "json",
                    url: "get_rooms.php",
                    data: {
                        building: building.id
                    },
                    success: function(rooms) {
                        if (rooms.length === 0) {
                            return;
                        }
                        
                        var filters = Filters.filters,
                            optgroup = $("<optgroup>")
                                            .attr("label", building.description);

                        selector.append(optgroup);

                        $.each(rooms, function(i, room) {
                            optgroup.append(
                                    $("<option>")
                                        .val(room.id)
                                        .attr("internal-type", "room")
                                        .prop("selected", $.inArray(room.id, filters.room) !== -1)
                                        .append(building.abbr + "-" + room.nmbr)
                            );
                        });
                    }
                 });
                results.push(async);
            });

            $.when.apply(this, results).done(function() {
                selector.multiselect({
                    buttonWidth: "100%",
                    maxHeight: 200,
                    enableClickableOptGroups: true,
                    disableIfEmpty: true,
                    onChange: onChange
                });

                if (onDone) {
                    onDone();
                }
            });
        }
    });
}

function initCalendar(eventClick) {
    $(function() {
        $("#content").fullCalendar({
            theme: true,
            height: "auto",
            header: {
                left: "prev,next today",
                center: "title",
                right: "month,agendaWeek,agendaDay"
            },
            events: function(start, end, timezone, callback) {
                var data = $.extend({start: start.format(), end: end.format()}, lastEventFilters);
                $.ajax({
                    url: "sections_feed.php",
                    cache: false,
                    data: data,
                    dataType: "json",
                    success: function(feed) {
                        callback(feed.events);

                        if (JSON.stringify(SemestersFooter.activeSemesters) !== JSON.stringify(feed.semesters)) {
                            SemestersFooter.activeSemesters = feed.semesters;
                        }
                    }
                });
            },
            eventClick: eventClick,
            eventRender: function(event, element) {
                element.css("cursor", "pointer");
            },
            weekends: false
        });
    });
}

function updateCalendar(filters) {
    lastEventFilters = filters;
    $("#content").fullCalendar("refetchEvents");
}

function initTable(filters) {
    $.ajax({
        dataType: "json",
        url: "table_feed.php",
        data: filters,
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
            $("#content").html(tableBuilder.getHTML());
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
