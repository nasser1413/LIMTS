<script type="text/javascript">

function onFormSubmitted() {
    var name = $("#semester-name").val();
    var type = $("#semester-type").val();
    var strd = $("#start-date").val();
    var endd = $("#end-date").val();

    if (!name || !type || !strd || !endd) {
        if (!name) {
            $("#semester-name").parents(".form-group").addClass("has-error");
        }

        if (!type) {
            $("#semester-type").parents(".form-group").addClass("has-error");
        }

        if (!strd) {
            $("#start-date").parents(".form-group").addClass("has-error");
        }

        if (!endd) {
            $("#end-date").parents(".form-group").addClass("has-error");
        }

        $("#warning-alert").offcanvas("show");
    } else {
        $.ajax({
		    dataType: "json",
		    url: "create_semester.php",
            data: {
                name: name,
                type: type,
                startDate: strd,
                endDate: endd
            },
            success: function(data) {
                if (data.response !== "Success") {
                    alert(data.response);
                } else {
                    window.location.href = "?page=calendar" + location.hash;
                }
            }
        });
    }
}

$(function() {
    $("#semester-name").change(function() {
        var parent = $("#semester-name").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

    $("#semester-type").change(function() {
        var parent = $("#semester-type").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

    $("#start-date").change(function() {
        var parent = $("#start-date").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

    $("#end-date").change(function() {
        var parent = $("#end-date").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
});

</script>

<h1>Add Semester</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="semesterName1">Semester Name:</label>
    <input type="text" class="form-control" id="semester-name" name="semesterName">
    </div>

    <div class="form-group">
    <label for="semesterTypeName1">Semester Type:</label>
    <input type-"text" class="form-control" id="semester-type" placeholder="FULL"  name="semesterTypeName">
	<p class= "help-block"><i>FOR EXAMPLE: "FULL" for full semester. "1st" and "2nd" for first and second 7 weeks.</i></p>
	</div>

    <div class="form-group">
    <label for="startDate1">Start Date:</label>
    <input type="text" class="form-control" id="start-date" placeholder="d/m/y" name="startDate">
    </div>

    <div class="form-group">
    <label for="startDate1">End Date:</label>
    <input type="text" class="form-control" id="end-date" placeholder="d/m/y" name="endDate">
    </div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
