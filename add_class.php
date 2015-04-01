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

    function onBuildingSelected() {
        var selectedBuilding = $("#buildingName1 option:selected").attr("_id");
	    $.ajax({
		    dataType: "json",
		    url: "get_rooms.php",
            data: {
                building: selectedBuilding
            },
		    success: function(data) {
			    var roomSelect = $("#roomName1");
                roomSelect.empty();
			    $.each(data, function(i, room) {
                    var id = room.id,
                        nmbr = room.nmbr,
                        capacity = room.cap;
                    roomSelect
                        .append($("<option>", { _id : id, _cap : capacity })
                        .text(nmbr));
                });
                if (data.length !== 0) {
                    roomSelect.prop("disabled", false);
                } else {
                    roomSelect.prop("disabled", true);
                }

                roomSelect.change(onRoomSelected);
                onRoomSelected();
		    }
	    });
    }

    function loadSelector(type, handler) {
       var url = "get_" + pluralize(type) + ".php";
       $.ajax({
		    dataType: "json",
		    url: url,
		    success: function(data) {
			    var selector = $("#" + type + "Name1");
			    $.each(data, function(i, object) {
                    var id = object.id,
                        abbr = object.name ? object.name : object.abbr;
                    selector
                        .append($("<option>", { _id : id, _abbr : abbr})
                        .text(abbr));
                });

                if (data.length !== 0) {
                    selector.prop("disabled", false);
                } else {
                    selector.prop("disabled", true);
                }

                if (handler) {
                    selector.change(handler);
                    handler(selector);
                }
		    }
	    });
    }

    loadSelector("building", onBuildingSelected);
    loadSelector("semester");
    loadSelector("professor");
    loadSelector("class");

    $(function() {
        $("#maxCapacity1").change(onCapacityChanged);
    });
</script>

<div class="alert alert-danger alert-fixed-top offcanvas" id="warningAlert1">
  <strong>Success!</strong> Your action has been completed succefully.
</div>

<h1>Add Class</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="className1">Class Name:</label>
    <input type="text" class="form-control" id="className1" name="className">
    </div>
	
    <div class="form-group">
    <label for="classTitleName1">Class Title:</label>
    <input type="text" class="form-control" id="classTitleName1" name="classTitleName">
	<p class= "help-block"><i>FOR EXAMPLE: GEM-206</i></p>
    </div>
	
    <div class="form-group">
    <label for="creditHours1">Credit Hours:</label>
    <input type="text" class="form-control" id="creditHours1" name="creditHours">
    </div>
	
    <div>
    <label for="contactHours1">Contact Hours:</label>
    <input type= "text" class="form-control" id="contactHours1" name="contactHours">
    </div>
	
    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
