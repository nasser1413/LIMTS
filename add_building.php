<script type="text/javascript">

function onFormSubmitted() {
    var name = $("#building-name").val();
    var abbr = $("#building-abbreviation").val();

    if (!name || !abbr) {
        if (!name) {
            $("#building-name").parents(".form-group").addClass("has-error");
        }

        if (!abbr) {
            $("#building-abbreviation").parents(".form-group").addClass("has-error");
        }

        $("#warning-alert").offcanvas("show");
    } else {
        $.ajax({
		    dataType: "json",
		    url: "create_building.php",
            data: {
                name: name,
                abbreviation: abbr
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
    $("#building-name").change(function() {
        var parent = $("#building-name").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

    $("#building-abbreviation").change(function() {
        var parent = $("#building-abbreviation").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
});

</script>

<div class="alert alert-danger alert-fixed-top offcanvas" id="warning-alert">
  <strong>Error!</strong> You must fill out the highlighted fields!
</div>

<h1>Add Building</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="buildingName1">Building Name:</label>
    <input type="text" class="form-control" id="building-name" name="buildingName">
    </div>

    <div class="form-group">
    <label for="buildingAbbName1">Building Abbreviation:</label>
    <input type="text" class="form-control" id="building-abbreviation" name="buildingAbbName">
    </div>


    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
