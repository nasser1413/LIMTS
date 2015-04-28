<script type="text/javascript">
var editId = getParameterByName("edit"),
    loadedBuilding;

function onFormSubmitted() {
    var name = $("#building-name").val(),
        abbr = $("#building-abbreviation").val();
    
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
		    url: "creators/create_building.php",
            data: {
                name: name,
                abbreviation: abbr,
                database_id: editId
            },
            success: function(data) {
                if (data.response !== "Success") {
                    alert(data.response);
                } else {
				 	$("#success-alert").offcanvas("show");
                }
            }
        });
    }
}
 
    
$(function() {
    // If "edit", load the existing data
    if (editId) {
        $("#form-header").text("Edit Building");
        ajaxLoadJSON("building", function(i, building) {
            loadedBuilding = building;
            $("#building-name").val(building.description);
            $("#building-abbreviation").val(building.abbr);
        }, {
            id: editId
        });
    }

    //Remove textbox error message  
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

<div class="alert alert-success alert-fixed-top offcanvas" id="success-alert">
  <strong>Success!</strong> You successfuly add a Building!
</div>

<h1 id="form-header">Add Building</h1>
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
