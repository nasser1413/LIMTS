        <!-- Include the table builder -->
        <script type="text/javascript" src="assets/js/limts.semesters-footer.js"></script>
        <script type="text/javascript" src="assets/js/wingpad.tablebuilder.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="assets/js/home.js"></script>
        <script type="text/javascript" type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js"></script>
        <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.css"></script>
        <link type="text/css" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.print.css" media="print"></script>
        <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />

        <script type="text/javascript">
        var calendarView = getParameterByName("page") === "calendar";

        function onFiltersChanged(option, checked, select) {
            if (checked) {
                Filters.add(option.attr("internal-type"), option.val());
            } else {
                Filters.remove(option.attr("internal-type"), option.val());
            }
        }

        function onHashChanged() {
            var filters = Filters.filters;

            if (calendarView) {
                updateCalendar(filters);
            } else {
                initTable(filters);
            }
        }

        if (calendarView) {
            initCalendar();
            SemestersFooter.mode = "checkboxes";
            SemestersFooter.jumpto = function(date) {
                $("#content").fullCalendar("gotoDate", date);
            };
        } else {
            $(function() {
                SemestersFooter.mode = "multi-selector";
            });
        }

        loadSelector("professor", onFiltersChanged);
        loadSelector("class", onFiltersChanged);
        loadRooms(onFiltersChanged, onHashChanged);
        Filters.hashchange = onHashChanged;
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
                    <label for="semester-selector" id="jump-label">Jump to:</label>
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
