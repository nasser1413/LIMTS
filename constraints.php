        <?php
            $semesters = $_GET["semesters"];

            if (!$semesters) {
                die("You must select semester(s).");
            }
        ?>

        <script>
            var semesters = "<?php echo $semesters ?>";

            function ajaxLoadJSON(type, handler, data) {
                return $.ajax({
                    dataType: "json",
                    url: "get_" + pluralize(type) + ".php",
                    data: data,
                    success: function(objects) {
                        $.each(objects, handler);
                    }
                });
            }

            $.ajax({
                type: "GET",
                url: "constraints.xml",
                dataType: "xml",
                success: function(xml){
                    $(xml).find("constraint").each(function(){
                        var information = $(this).find("scope").text() + " " + $(this).find("type").text();
                        var time = $(this).find("time").text();
                        var reason = $(this).find("reason").text();
                        $("<li></li>").html(information + " during " + time + " because " + reason).appendTo("#constraints");
                    });
                },
                error: function() {
                    alert("An error occurred while processing XML file.");
                }
            });

            ajaxLoadJSON("semesters", function(i, semesters) {
                if (i > 0) {
                    semesters.name = ", " + semesters.name;
                }
                $("#semesters").append(document.createTextNode(semesters.name));
            }, {
                id: semesters            
            });

            ajaxLoadJSON("professor", function(i, professor) {
                var id = professor.id;
                var creditHours = 0;

                var ajax = ajaxLoadJSON("section", function(i, section) {
                    creditHours += parseInt(section.credit_hours);
                }, {
                    professors: id,
                    semesters: semesters
                });

                $.when(this, ajax).done(function() {
                    var textClass = "text-";
                    
                    if (creditHours > professor.max_credit_hours) {
                        textClass += "danger";
                    } else if (creditHours === professor.max_credit_hours) {
                        textClass += "warning";
                    } else {
                        textClass += "success";
                    }

                    $("<li></li>").addClass(textClass).html("Prof. " + professor.name + " is teaching " + creditHours + " out of his max " + professor.max_credit_hours).appendTo("#professors");
                });
            });
        </script>

        <h1 id="semesters"></h1>

        <h2>Constraints:</h2>
        <ul id="constraints"></ul>

        <h2>Professors:</h2>
        <ul id="professors"></ul>
