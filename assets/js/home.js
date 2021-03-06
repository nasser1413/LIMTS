var lastEventFilters = {},
    firstLoad = true;

function initCalendar(eventClick) {
    getSemesterStart(function(minDate) {
        $(function() {
            $("#content").fullCalendar({
                theme: true,
                height: "auto",
                defaultDate: minDate,
                header: {
                    left: "prev,next today",
                    center: "title",
                    right: "month,agendaWeek,agendaDay"
                },
                events: function(start, end, timezone, callback) {
                    var data = $.extend({start: start.format(), end: end.format()}, lastEventFilters);
                    $.ajax({
                        url: "getters/sections_feed.php",
                        cache: false,
                        data: data,
                        dataType: "json",
                        success: function(feed) {
                            callback(feed.events);

                            // TODO: Fix this logic
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
        })
    });
}

function updateCalendar(filters) {
    lastEventFilters = filters;

    var calDate = $("#content").fullCalendar("getDate");
    getSemesterStart(function(minDate) {
        if (firstLoad && minDate && !minDate.isSame(calDate)) {
            $("#content").fullCalendar("gotoDate", minDate);
        } else {
            $("#content").fullCalendar("refetchEvents");
        }
        firstLoad = false;
    });
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');

    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];

        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }

    return null;
}

function getSemesterStart(done) {
    var filters = Filters.filters.semester;
    var semester;

    if ((semester = getCookie("semester")) && (semester != "Invalid date")) {
        done(moment(semester));
    } else if (filters) {
        var minDate = Infinity;

        var response = ajaxLoadJSON("semester", function(i, semester) {
            if (semester.start < minDate) {
                minDate = semester.start;
            }
        }, {
            id: filters
        });

        $.when(response).done(function() {
            minDate = moment.unix(minDate);
            done(minDate);
        });
    } else {
        done(undefined);
    }
}

function initTable(filters) {
    $.ajax({
        dataType: "json",
        url: "getters/table_feed.php",
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
                        if (!(key == "database_id" || key == "meeting_type")) {
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
