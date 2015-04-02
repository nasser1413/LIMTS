<script type="text/javascript">
    var modifiedCapacity = false,
        rowId = 0,
        rowTemplate;

    function onFormSubmitted() {
        $("#warningAlert1").offcanvas('show');
    }

    function onCapacityChanged() {
        modifiedCapacity = true;
    }

    function onRoomSelected() {
        var selectedCapacity = $("#roomName1 option:selected").attr("_cap");
        if (!modifiedCapacity && selectedCapacity) {
            $("#max-capacity").val(selectedCapacity);
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

    function loadSelector(type, handler, offset) {
       var url = "get_" + pluralize(type) + ".php";
       $.ajax({
		    dataType: "json",
		    url: url,
		    success: function(data) {
			    var selector = $("#" + type + "-name" + (offset || ""));
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

    function addRow() {
        var row = $(rowTemplate);
        rowId++;

        row.find("#building-name").attr("id", "building-name" + rowId);
        row.find("#room-name").attr("id", "room-name" + rowId);
        row.find("#meeting-time").attr("id", "meeting-time" + rowId);
        row.find("#del-button").attr("id", "del-button" + rowId);

        $("#meeting-times").append(row);

        loadSelector("building", onBuildingSelected, rowId);
    }

    loadSelector("semester");
    loadSelector("professor");
    loadSelector("class");

    $(function() {
        rowTemplate = $("#row-template").html();
        $("#add-row").click(addRow);
        $("#add-row").css("cursor", "pointer");
        $("#max-capacity").change(onCapacityChanged);
    });
</script>

<div class="alert alert-danger alert-fixed-top offcanvas" id="warningAlert1">
  <strong>Failure!</strong> No backend has been implemented yet!
</div>

<script id="row-template" type="text/x-custom-template">
    <tr>
        <td class="col-xs-2">
            <select class="form-control" id="building-name" name="buildingName" disabled>
            </select>
        </td>

        <td class="col-xs-2">
            <select class="form-control" id="room-name" name="roomName" disabled>
            </select>
        </td>

        <td class="col-xs-8">
            <div class="input-group">
                <input type="text" class="form-control" id="meeting-time" name="meetingTime" placeholder="MWF 9:00a-9:50a">
                <span class="glyphicon glyphicon-remove input-group-addon" id="del-button"></span>
            </div>
        </td>
    </tr>
</script>

<h1>Add Section</h1>
<form action="javascript:onFormSubmitted()" id="mainForm">
    <div class="form-group">
    <label for="className1">Name:</label>
    <select class="form-control" id="class-name" name="className" disabled>
    </select>
    </div>

    <div class="form-group">
    <label for="professorName1">Professor:</label>
    <select class="form-control" id="professor-name" name="professorName" disabled>

    </select>
    </div>

    <div class="form-group">
    <label for="semesterName1">Semester:</label>
    <select class="form-control" id="semester-name" name="semesterName" disabled>

    </select>
    </div>

    <div class="form-group">
    <label for="max-capacity">Max Capacity:</label>
    <input type="text" class="form-control" id="max-capacity" name="maxCapacity">
    <p class="help-block"><i>If left blank this will be populated based on the selected room(s)</i></p>
    </div>

    <div class="form-group">
    <label for="classIdentifier1">Identifier:</label>
    <input type="text" class="form-control" id="classIdentifier1" placeholder="A" name="classIdentifier">
    </div>

    <div class="form-group" style="margin-bottom: 5px;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Building</th>
                    <th>Room</th>
                    <th>Meeting Time</th>
                    <th><span class="glyphicon glyphicon-plus vertical-align" style="float: right;" id="add-row"></span></th>
                </tr>
            </thead>

            <tbody id="meeting-times"></tbody>
        </table>
    </div>

    <div class="form-group">
    <input type="submit" class="btn btn-default" value="Submit">
    </div>

</form>
