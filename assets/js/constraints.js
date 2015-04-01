function initConstraints(semesters) {
    $("#constraints").empty();
    $("#professors").empty();
    $("#semesters").empty();

    $.ajax({
        type: "GET",
        url: "constraints.xml",
        dataType: "xml",
        success: function(xml){
            $(xml).find("constraint").each(function(){
                var scope = $(this).find("scope").text();
                var time = $(this).find("time").text();
                var reason = $(this).find("reason").text();
                var type =  $(this).find("type").text();
                if (scope !== "global") {
                    var id = $(this).find("object").text();

                    ajaxLoadJSON(scope, function(i, object) {
                        var name = (object.name || object.abbr || (scope + " " + id));
                        $("<li></li>").html(name + " " + type + " during " + time + " because " + reason).appendTo("#constraints");
                    }, {
                        id: id
                    });
                } else {
                    $("<li></li>").html(scope + " " + type + " during " + time + " because " + reason).appendTo("#constraints");
                }
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
            professor: id,
            semester: semesters
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
}
