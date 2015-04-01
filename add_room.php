<script type="text/javascript">

var modifiedCapacity = false;

function onFormSubmitted() {
    $("#warningAlert1").offcanvas('show');
}

function onCapacityChanged() {
    modifiedCapacity = true;
}

function onRoomSelected() {
    var selectedCapacity = $("#roomName1 option:selected").attr("_cap");
    if (!modifiedCapacity && selectedCapacity) {
        $("#maxCapacity1").val(selectedCapacity);
    }
}

loadSelector("room");
loadSelector("buliding");
$(function() {
    $("#maxCapacity1").change(onCapacityChanged);
});
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warningAlert1">
  <strong>Success!</strong> Your action has been completed succefully.
</div>

<h1>Add Room</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="roomName1">Room Name:</label>
    <input type="text" class="form-control" id="roomName1" name="roomName">
    </div>
	
    <div class="form-group">
    <label for="roomNum1">Room Number:</label>
    <select class="form-control" id="roomNum1" name="roomNum">
    </select>
	</div>
	
    <div class="form-group">
    <label for="maxCapacity1">Room Capacity:</label>
    <input type="text" class="form-control" id="maxCapacity1" name="maxCapacity">
    </div>
     
    <div class="form-group">
    <label for="handicapAccessible1">Handicap Accessible:</label>
    <select class="form-control" id="handicapAccessible1" name= "handicapAccessible">
    </select>
	</div>
	
    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>