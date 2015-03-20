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
       var url = "get_" + type + (type.endsWith("s") ? "es" : "s") + ".php";
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

<h1>Add Section</h1>
<form action="javascript:onFormSubmitted()">
    <div class="form-group">    
    <label for="className1">Name:</label>
    <select class="form-control" id="className1" name="className" disabled>
    </select>
    </div>

    <div class="form-group">
    <label for="professorName1">Professor:</label>
    <select class="form-control" id="professorName1" name="professorName" disabled>

    </select>
    </div>

    <div class="form-group">
    <label for="semesterName1">Semester:</label>
    <select class="form-control" id="semesterName1" name="semesterName" disabled>

    </select>
    </div>

    <div class="form-group">
    <label for="maxCapacity1">Max Capacity:</label>
    <input type="text" class="form-control" id="maxCapacity1" name="maxCapacity">
    <p class="help-block"><i>If left blank this will be populated based on the selected room(s)</i></p>
    </div>

    <div class="form-group"> 
    <label for="classIdentifier1">Identifier:</label>
    <input type="text" class="form-control" id="classIdentifier1" placeholder="A" name="classIdentifier">
    </div>

    <div class="form-group">
        <label for="meetingGroup1">Meeting Time:</label>
        <div class="row" id="meetingGroup1">
            <div class="col-xs-2">
                <select class="form-control" id="buildingName1" name="buildingName" disabled>
                </select>
            </div>
            <div class="col-xs-2">
                <select class="form-control" id="roomName1" name="roomName" disabled>
                </select>
            </div>

            <div class="col-xs-8">
                <div class="input-group">
                    <input type="text" class="form-control" id="meetingTime1" name="meetingTime" placeholder="MWF 9:00a-9:50a">
                    <!-- <span class="input-group-btn"> -->
                        <!-- <input type="button" id="delButton1" name="delButton" value="Delete" class="btn btn-default"> -->
                        <span class="glyphicon glyphicon-remove input-group-addon" id="delButton1"></span>
                    <!-- </span> -->
                </div>
            </div>
        </div>    
    </div>

    <div class="form-group">
    <input type="button" value="Add Another Meeting Time" id="addAnotherMeetingTime1" name="addAnotherMeetingTime" class="btn btn-default">
    </div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
