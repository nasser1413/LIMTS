<script type="text/javascript">
var editId = getParameterByName("edit"),
    loadedProfessor;    
    
function onFormSubmitted() {
    var name = $("#professor-name").val();
    var professorType = parseInt($("#professortype-selector option:selected").val());
    var maxCreditHours = $("#max-credit-hours").val();
    var valpoId = $("#valpo-id").val();
  
    if (!valpoId) {
        $("#valpo-id").parents(".form-group").addClass("has-warning");
    }

    if (!name || !maxCreditHours || !professorType) {
        if (!name) {
            $("#professor-name").parents(".form-group").addClass("has-error");
        }

        if (!maxCreditHours) {
            $("#max-credit-hours").parents(".form-group").addClass("has-error");
        }

        if (!professorType) {
            $("#professortype-selector").parents(".form-group").addClass("has-error");
        }

        $("#warning-alert").offcanvas("show");
    } else {
        $.ajax({
            dataType: "json",
            url: "creators/create_professor.php",
            data: {
            name: name,
                maxCreditHours: maxCreditHours,
                professorType: professorType,
                valpoId: valpoId,
                id: editId
            },
            success: function(data) {
                if (data.response !== "Success") {
                    alert(data.response);	
                } else {
                    $("#success-alert").offcanvas("show");
                }
            }
        }); // ajax 
    }// else
}// close Class On FormSubmitted 

$(function() {
    // If "edit", load the existing data
    if (editId) {
       $("#form-header").text("Edit Professor");
       ajaxLoadJSON("professor", function(i, professor) {
           loadedProfessor = professor;
           $("#professor-name").val(professor.name);
           $("#max-credit-hours").val(professor.max_credit_hours);
           $("#valpo-id").val(professor.valpo_id);
       });
    }
    
	$("#professor-name").change(function() {
        var parent = $("#professor-name").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

	$("#max-credit-hours").change(function() {
        var parent = $("#max-credit-hours").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

	$("#valpo-id").change(function() {
        var parent = $("#valpo-id").parents(".form-group");
        parent.removeClass("has-warning");
        parent.addClass("has-success");
    });
	
	loadSelector("professortype", function() {
		var selected = $("#professortype-selector option:selected").val();
	    var parent = $("#professortype-selector").parents(".form-group");
	
		if (selected != 0) {
		    parent.removeClass("has-error");
		    parent.addClass("has-success");
		} else {
		    parent.addClass("has-error");
		    parent.removeClass("has-success");
		}
        
        ajaxLoadJSON("professortype", function(i, type) {
            $("#max-credit-hours").val(type.hours);
            $("#max-credit-hours").change();
        }, {
            id: selected
        });
	}, {
	    multiselect: false,
	    addBlank: true,
        done: function () {
            $('#professortype-selector option[value="' + loadedProfessor.professor_type + '"]').prop("selected", true);
        }
	}); // close loadSelector
}); // close function 
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warning-alert">
  <strong>Error!</strong> You must fill out the highlighted fields!
</div>

<div class="alert alert-success alert-fixed-top offcanvas" id="success-alert">
  <strong>Success!</strong> You successfuly add a Professor!
</div>


<h1 id="form-header">Add Professor</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="professor-name">Professor Name:</label>
    <input type="text" class="form-control" id="professor-name" name="professorName">
    </div>

    <div class="form-group">
    <label for="professortype-selector">Professor Type:</label>
    <select class="form-control" id="professortype-selector" name="professortype">
    </select>
	</div>

    <div class="form-group">
    <label for="max-credit-hours">Max Credit Hours:</label>
    <input type="text" class="form-control" id="max-credit-hours" name="max-credit-hours">
    <p class="help-block"><i>If left blank this will be populated based on the selected type</i></p>
    </div>

	<div class="form-group">
    <label for="valpo-id">Valparaiso ID:</label>
    <input type="text" class="form-control" id="valpo-id" name="valpoId">
    </div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
