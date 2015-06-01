<script type="text/javascript">
var editId = getParameterByName("edit"),
    loadedType;

function onFormSubmitted() {
	var typeName = $("#type-name").val();
	var creditHours = $("#credit-hours").val();

	if (!typeName || !creditHours) {
        if (!typeName) {
            $("#type-name").parents(".form-group").addClass("has-error");
        }

		if (!creditHours) {
            $("#credit-hours").parents(".form-group").addClass("has-error");
        }

        $("#warning-alert").offcanvas("show");
    } else {
		$.ajax({
			dataType: "json",
			url: "creators/create_professortype.php",
			data: {
				name: typeName,
                creditHours: creditHours,
                id: editId
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
       // If "edit", load the existing data
    if (editId) {
       $("#form-header").text("Edit Professor Type");
       ajaxLoadJSON("professortype", function(i, type) {
           loadedType = type;
           $("#type-name").val(type.name);
           $("#credit-hours").val(type.hours);
       }, {
           id: editId
       });
    }

  	$("#type-name").change(function() {
        var parent = $("#type-name").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

 	$("#credit-hours").change(function() {
        var parent = $("#credit-hours").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
});
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warning-alert">
	<strong>Error!</strong> You must fill out the highlighted fields!
</div>

<h1 id="form-header">Add Professor Type</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
        <label for="room-number">Name:</label>
	    <input type="text" class="form-control" id="type-name" name="type-name">
    </div>

    <div class="form-group">
	    <label for="room-number">Default Max Teaching Load Credits:</label>
	    <input type="number" class="form-control" id="credit-hours" name="credit-hours">
	</div>

    <div class="form-group">
    	<input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
