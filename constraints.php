        <script type="text/javascript" src="assets/js/constraints.js"></script>
        <script type="text/javascript" src="assets/js/limts.semesters-footer.js"></script>

        <script type="text/javascript">
            function onHashChanged() {
                var filters = Filters.filters;

                initConstraints(filters.semester);
            }

            $(function() {
                SemestersFooter.mode = "multi-selector";
                Filters.hashchange = onHashChanged;
                onHashChanged();
            });
        </script>

        <h1 id="semesters"></h1>

        <h2>Constraints:</h2>
        <ul id="constraints"></ul>

        <h2>Professors:</h2>
        <ul id="professors"></ul>

        <div id="footer">
            <form class="form-inline">
                <div class="form-group" style="float:right">
                    <label for="semester-selector" id="jump-label">Jump to:</label>
                    <select id="semester-selector" class="form-control">
                    </select>
                </div>
            </form>
        </div>
