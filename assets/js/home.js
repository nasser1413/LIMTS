var lastEventFilters = {},
    activeSemesters = null;

function loadSelector(type, handler, disableMultiselect, addBlank) {
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

            if (addBlank) {
                $("<option>", { value: '0', selected: true }).prependTo(selector);
            }

            if (!disableMultiselect) {
                selector.multiselect({
                    buttonWidth: "100%",
                    maxHeight: 200,
                    disableIfEmpty: true,
                    onChange: handler
                });
            } else {
                selector.change(handler);
            }
        }
    });
}

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
                        var optgroup = $("<optgroup label=\"" + building.description + "\"></optgroup>");
                        selector.append(optgroup);

                        $.each(rooms, function(i, room) {
                            optgroup.append("<option value=\"" + room.id + "\" internal-type=\"room\">" + building.abbr + "-" + room.nmbr + "</option>");
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

function initCalendar(semestersLoaded) {
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
                        activeSemesters = feed.semesters;
                        if (semestersLoaded) {
                            semestersLoaded();
                        }
                    }
                });
            },
            eventClick: function(calEvent, jsEvent, view) {
                alert("Event: " + calEvent.title);
            },
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

function addFilter(type, id) {
    var filters = $.deparam(location.hash.substr(1));
    if (filters[type] === undefined) {
        filters[type] = [ id ];
    } else if ($.inArray(id, filters[type]) === -1) {
        filters[type].push(id);
    }
    location.hash = $.param(filters);
}

function removeFilter(type, id) {
    var filters = $.deparam(location.hash.substr(1));
    var index = $.inArray(id, filters[type]);
    if (index !== -1) {
        filters[type].splice(index, 1);
    }
    location.hash = $.param(filters);
}