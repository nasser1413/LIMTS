<script type="text/javascript">
var editId = getParameterByName("edit"),
    loadedProfessor;    
    
function onFormSubmitted() {
    var name = $("#professorName1").val();
    var ProfessorType = $("#professortype-selector option:selected").val();
    var MaxCreditHours = $("#MaxCreditHours1").val();
    var ValpoId = $("#valpoId1").val();
  

if (!name || !MaxCreditHours || !ProfessorType|| !ValpoId) {

	if (!name) {
            $("#professorName1").parents(".form-group").addClass("has-error");
        }

	if (!MaxCreditHours) {
            $("#MaxCreditHours1").parents(".form-group").addClass("has-error");
        }
	if (!ProfessorType) {
            $("#professortype-selector").parents(".form-group").addClass("has-error");
        }
	if (!ValpoId) {
            $("#valpoId1").parents(".form-group").addClass("has-warning");
		
        }

	 $("#warning-alert").offcanvas("show");

	}else {
	   $.ajax({
	           dataType: "json",
		   url: "create_professor.php",
                 data: {
              		  name: name,
              		  MaxCreditHours: MaxCreditHours,
			  ProfessorType:ProfessorType ,
			  ValpoId:ValpoId
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
           $("#professortype-selector").val(professor.professor_type);
           $("#max-credit-hours").val(professor.max_credit_hours);
           $("#valpo-id").val(professor.valpo_id);
       });
    }
    
    
	$("#professorName1").change(function() {
        var parent = $("#professorName1").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
	$("#MaxCreditHours1").change(function() {
        var parent = $("#MaxCreditHours1").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
	$("#valpoId1").change(function() {
        var parent = $("#valpoId1").parents(".form-group");
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
	}, {
	    multiselect: false,
	    addBlank: true
	}); // close loadSelector
}); // close function 
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warning-alert">
  <strong>Error!</strong> You must fill out the highlighted fields!
</div>

<div class="alert alert-warning alert-fixed-top offcanvas" id="warning-alert">
  <strong>Warning!</strong> You have not add Valpo Id!
</div>

<div class="alert alert-success alert-fixed-top offcanvas" id="success-alert">
  <strong>Success!</strong> You successfuly add a Professor!
</div>


<h1 id="form-header">Add Professor</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="professorName1">Professor Name:</label>
    <input type="text" class="form-control" id="professor-name" name="professorName">
    </div>

    <div class="form-group">
    <label for="professortype-selector">Professor Type:</label>
    <select class="form-control" id="professortype-selector" name="professortype">
    </select>
	</div>

    <div class="form-group">
    <label for="MaxCreditHours1">Max Credit Hours:</label>
    <input type="text" class="form-control" id="max-credit-hours" name="MaxCreditHours">
    <p class="help-block"><i>If left blank this will be populated based on the selected type</i></p>
    </div>

	<div class="form-group">
    <label for="valpoId1">Valparaiso ID:</label>
    <input type="text" class="form-control" id="valpo-id" name="valpoId">
    </div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>

