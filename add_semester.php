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

<h1>Add Semester</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">

    <div class="form-group">
    <label for="semesterName1">semester Name:</label>
    <input type="text" class="form-control" id="semesterName1" name="semesterName">
    </div>
	
    <div class="form-group">
    <label for="semesterTypeName1">Semester Type:</label>
    <input type-"text" class="form-control" id="semesterTypeName1" placeholder="FULL"  name="semesterTypeName">
	<p class= "help-block"><i>FOR EXAMPLE: "FULL" for full semester. "1st" and "2nd" for first and second 7 weeks.</i></p>
	</div>
	
    <div class="form-group">
    <label for="startDate1">Start Date:</label>
    <input type="text" class="form-control" id="startDate1" placeholder="d/m/y" name="startDate">
    </div>
	
    <div class="form-group">
    <label for="startDate1">End Date:</label>
    <input type="text" class="form-control" id="endDate1" placeholder="d/m/y" name="endDate">
    </div>
	
    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>