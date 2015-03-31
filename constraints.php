        <script type="text/javascript" src="assets/js/constraints.js"></script>

        <script type="text/javascript">
            function onHashChanged() {
                var filters = $.deparam(location.hash.substr(1));

                initConstraints(filters.semester);
            }

            $(function() {
                $(window).bind("hashchange", onHashChanged);
                onHashChanged();
            });
        </script>

        <h1 id="semesters"></h1>

        <h2>Constraints:</h2>
        <ul id="constraints"></ul>

        <h2>Professors:</h2>
        <ul id="professors"></ul>
