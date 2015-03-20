    <!-- Include the table builder -->
    <script src='assets/js/wingpad.tablebuilder.js'></script>
    <script src='//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js'></script>
    <link type="text/css" rel="stylesheet" href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" />

    <script type="text/javascript">
    $.ajax({
        dataType: "json",
        url: "table_feed.php",
        success: function(data) {
            // Instanstiate a new TableBuilder
            var tableBuilder = new TableBuilder({
                    'id': 'sections'
                });

            // Start the table's header
            tableBuilder.startElement('thead');
            // Add a Row of class 'header_row'
            tableBuilder.addRow({
                    'class': 'header_row'
                });

            var headers = data.columns;
            for (var i = 0; i < headers.length; i++) {
                tableBuilder.addData(headers[i], {
                        'class': 'header_item'
                    });
            }

            // End the table's header
            tableBuilder.endElement('thead');

            // Start the table's body
            tableBuilder.startElement('tbody');

            var sections = data.sections;
            for (var i = 0; i < sections.length; i++) {
                var section = sections[i];

                // start a new row (of class 'section_row' & id 'row_XXXXXXX')
                tableBuilder.addRow({
                        'class': 'section_row',
                        'id': 'row_' + section.database_id
                    });
                
                for (var key in section) {
                    if (section.hasOwnProperty(key)) {
                        if (key !== "database_id") {
                            tableBuilder.addData(section[key], {
                                    'class': key                                
                                });
                        }
                    }
                }
            }

            // End the table's body
            tableBuilder.endElement('tbody');

            // Finalize the table
            tableBuilder.finalize();

            // Replace the placeholder with the HTML from the tablebuilder
            $('#sections').replaceWith(tableBuilder.getHTML());
            $("#sections").DataTable({
                "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                }],
                "paging": false
            });
        }
    });
    </script>

    <table id="sections"></table>

    <style>

    #sections {
        width: 100%;
        height: 100%;
    }

    </style>
