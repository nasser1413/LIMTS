<script type="text/javascript">

function onFormSubmitted() {

	var building = $("#building-selector option:selected").val();
	var number = $("#room-number").val();
	var capacity = $("#maxCapacity1").val();
	var handicap_accessible = $("#").val();

	if (!building ||!number || !capacity) {
        if (!building) {
            $("#building-selector").parents(".form-group").addClass("has-error");
        }
	 if (!number) {
            $("#room-number").parents(".form-group").addClass("has-error");
        }
     	if (!capacity) {
            $("#maxCapacity1").parents(".form-group").addClass("has-error");
        }  

  $("#warning-alert").offcanvas("show");

}
}
$(function() {

 	$("#building-selector option:selected").change(function() {
        var parent = $("#building-selector").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

  	$("#room-number").change(function() {
        var parent = $("#room-number").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });
 	$("#maxCapacity1").change(function() {
        var parent = $("#maxCapacity1").parents(".form-group");
        parent.removeClass("has-error");
        parent.addClass("has-success");
    });

$('div.btn-group .btn').click(function(){
  $(this).find('input:radio').attr('checked', true);
  alert($('input[name=radio-btn-ctrl]:checked').val());
});

	loadSelector("building", function() {
    alert("you selected something...");
}, {
    addBlank: true,
    multiselect: false
});

});
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warning-alert">
  <strong>Error!</strong> You must fill out the highlighted fields!
</div>

<h1>Add Room</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="building-selector">Building:</label>
    <select class="form-control" id="building-selector" name="building">
    </select>
    </div>

    <div class="form-group">
    <label for="room-number">Room Number:</label>
    <input type="text" class="form-control" id="room-number" name="room-number">
	</div>

    <div class="form-group">
    <label for="maxCapacity1">Room Capacity:</label>
    <input type="text" class="form-control" id="maxCapacity1" name="maxCapacity">
    </div>

    <div class="form-group">
    <label for="handicapAccessible1">Handicap Accessible:</label>
    <label class="radio-inline"><input type="radio" name="optradio" >Yes</label>
    <label class="radio-inline"><input type="radio" name="optradio" >No</label>	
</div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
