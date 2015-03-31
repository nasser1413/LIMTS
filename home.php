        <!-- Include the table builder -->
        <script type="text/javascript" src="assets/js/wingpad.tablebuilder.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="assets/js/home.js"></script>
        <script type="text/javascript" type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js"></script>
        <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.css"></script>
        <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.print.css" media="print"></script>
        <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />

        <script type="text/javascript">
        var calendarView = <?php echo json_encode(!$_GET["table"]) ?>;

        function onCalendarSemestersLoaded() {
            var checkboxGroup = $("#semesters-group");
            checkboxGroup.empty();

            $.each(activeSemesters, function(i, semester) {
                checkboxGroup.append('<label class="checkbox-inline"><input type="checkbox" id="semesterCheckbox' +
                                        semester.id + '" value="option1" checked> ' + semester.name + '</label>');
            });
        }

        function onSemesterSelected() {
            $("select option:selected").each(function() {
                var option = this;

                $.ajax({
                    dataType: "json",
                    url: "get_semesters.php",
                    data: {
                        id: option.value
                    },
                    success: function(semesters) {
                        if (semesters[0]) {
                            $("#content").fullCalendar("gotoDate", moment.unix(semesters[0].start));
                            $("#semester-selector").val("0");
                        }
                    }
                });
            });
        }

        function onFiltersChanged(option, checked, select) {
            if (checked) {
                addFilter(option.attr("internal-type"), option.val());
            } else {
                removeFilter(option.attr("internal-type"), option.val());
            }
        }

        function onHashChanged() {
            var filters = $.deparam(location.hash.substr(1));
            // var jsonFilters = {};

            for (var filter in filters) {
                if (filters.hasOwnProperty(filter)) {
                    $("#" + filter + "-selector").multiselect("select", filters[filter]);
                    // jsonFilters[pluralize(filter)] = JSON.stringify(filters[filter]);
                }
            }

            if (calendarView) {
                updateCalendar(filters);
            } else {
                initTable(filters);
            }
        }

        if (calendarView) {
            initCalendar(onCalendarSemestersLoaded);
        } else {
            $(function() {
                $("#semester-selector").prop("disabled", true);
            });
        }

        loadSelector("professor", onFiltersChanged);
        loadSelector("semester", onSemesterSelected, true, true);
        loadSelector("class", onFiltersChanged);
        loadRooms(onFiltersChanged, onHashChanged);

        $(function() {
            $(window).bind('hashchange', onHashChanged);
        });
        </script>

        <div class="btn-group btn-group-justified" id="main-btn-group" role="group">
            <select id="room-selector" multiple="multiple">
            </select>

            <select id="class-selector" multiple="multiple">
            </select>

            <select id="professor-selector" multiple="multiple">
            </select>
        </div>

        <div id="content"></div>

        <div id="footer">
            <form class="form-inline">
                <div class="form-group">
                    <label for="semesters-group">Semesters:</label>
                    <div class="form-group" id="semesters-group">
                    </div>
                </div>
                <div class="form-group" style="float:right">
                    <label for="semester-selector">Jump to:</label>
                    <select id="semester-selector" class="form-control">
                    </select>
                </div>
            </form>
        </div>

        <style>
        #sections {
            width: 100%;
            height: 100%;
        }
        </style>
