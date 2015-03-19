<script type="text/javascript">

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
                        nmbr = room.nmbr;
                    roomSelect
                        .append($("<option>", { "_id" : id })
                        .text(nmbr)); 
                });
                if (data.length !== 0) {
                    roomSelect.prop("disabled", false);
                } else {
                    roomSelect.prop("disabled", true);
                }
		    }
	    });
    }

    function loadSelector(type, handler) {
       $.ajax({
		    dataType: "json",
		    url: "get_" + type + "s.php",
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
                    selector.change(handler, selector);
                    handler(selector);
                }
		    }
	    });
    }

    loadSelector("building", onBuildingSelected);
    loadSelector("semester");
    loadSelector("professor");
    loadSelector("classe");

</script>

<h1>Add Section</h1>
<form action="" method="POST">
    <div class="form-group">    
    <label for="classeName1">Name:</label>
    <select class="form-control" id="classeName1" name="classeName" disabled>
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

            <div class="col-xs-7">
                <input type="text" class="form-control" id="meetingTime1" name="meetingTime" placeholder="MWF 9:00a-9:50a">
            </div>
            <div class="col-xs-1">
                <input type="button" id="delButton1" name="delButton" value="Delete" class="btn btn-default">
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
