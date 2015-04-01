<script type="text/javascript">
loadSelector("building", function() {
    alert("you selected something...");
}, true, false);
</script>


<div class="alert alert-danger alert-fixed-top offcanvas" id="warningAlert1">
  <strong>Success!</strong> Your action has been completed succefully.
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
    <!-- <div class="radio"> -->
        <label class="radio-inline"><input type="radio" name="optradio">Yes</label>
    <!-- </div> -->
    <!-- <div class="radio"> -->
        <label class="radio-inline"><input type="radio" name="optradio">No</label>
    <!-- </div> -->
	</div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
