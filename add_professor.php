<script type="text/javascript">
loadSelector("professortype", function() {
    alert("you selected something...");
}, {
    multiselect: false,
    addBlank: true
});
</script>

<h1>Add Professor</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="professorName1">Professor Name:</label>
    <input type="text" class="form-control" id="professorName1" name="professorName">
    </div>

    <div class="form-group">
    <label for="professortype-selector">Professor Type:</label>
    <select class="form-control" id="professortype-selector" name="professortype">
    </select>
	</div>

    <div class="form-group">
    <label for="MaxCreditHours1">Max Credit Hours:</label>
    <input type="text" class="form-control" id="MaxCreditHours1" name="MaxCreditHours">
    <p class="help-block"><i>If left blank this will be populated based on the selected type</i></p>
    </div>

	<div class="form-group">
    <label for="valpoId1">Valparaiso ID:</label>
    <input type="text" class="form-control" id="valpoId1" name="valpoId">
    </div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
