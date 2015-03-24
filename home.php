        <!-- Include the table builder -->
        <script src="assets/js/wingpad.tablebuilder.js"></script>
        <script src="assets/js/bootstrap-multiselect.js"></script>
        <script src="assets/js/jquery-deparam.js"></script>
        <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/home.js"></script>

        <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />
        <link type="text/css" rel="stylesheet" href="assets/css/bootstrap-multiselect.css" />

        <script type="text/javascript">
        var calendarView = <?php echo json_encode(!$_GET["table"]) ?>;

        if (calendarView) {
            initCalendar();
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
            var jsonFilters = {};

            for (var filter in filters) {
                if (filters.hasOwnProperty(filter)) {
                    $("#" + filter + "-selector").multiselect("select", filters[filter]);
                    jsonFilters[pluralize(filter)] = JSON.stringify(filters[filter]);
                }
            }
            
            if (calendarView) {
                updateCalendar(jsonFilters);
            } else {
                initTable(jsonFilters);
            }
        }

        loadSelector("professor", onFiltersChanged);
        loadSelector("class", onFiltersChanged);
        loadRooms(onFiltersChanged, onHashChanged);

        $(function() {
            $(window).bind('hashchange', onHashChanged);
        });
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
