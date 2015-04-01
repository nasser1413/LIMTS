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

<h1>Add Building</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="buildingName1">Building Name:</label>
    <input type="text" class="form-control" id="buildingName1" name="buildingName">
    </div>
	
    <div class="form-group">
    <label for="buildingAbbName1">Building Abbreviation:</label>
    <input type="text" class="form-control" id="buildingAbbName1" name="buildingAbbName">
    </div>
	

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>