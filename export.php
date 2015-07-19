<script type="text/javascript" src="assets/js/export.js"></script>
<script src="https://apis.google.com/js/client.js?onload=checkAuth"></script>

<div id="content" class="container-fluid">
  <div id="authorize-div" class="row" style="display: none">
    <span>Authorize access to Google Calendar API</span>
    <!--Button for the user to click to initiate auth sequence -->
    <button id="authorize-button" onclick="handleAuthClick(event)">
      Authorize
    </button>
  </div>

  <div id="form-div" class="row" style="display: none">
    <h1 id="form-header">Export Professor's Sections</h1>
    <form action="javascript:onFormSubmitted()" id="export-form">
      <div class="form-group">
        <label for="professor-selector">Professor:</label>
        <select class="form-control" id="professor-selector" name="professor" disabled>
        </select>
      </div>

      <div class="form-group">
        <label for="semester-selector">Semester:</label>
        <select class="form-control" id="semester-selector" name="semester" disabled>
        </select>
      </div>

      <div class="form-group">
         <label for="timezone-selector">Semester:</label>
         <select class="form-control" id="timezone-selector" name="timezone">
           <?php
             $timezone_identifiers = DateTimeZone::listIdentifiers();

             for ($i = 0; $i < count($timezone_identifiers); $i++) {
               if ($timezone_identifiers[$i] == "America/Chicago") {
                 echo "<option selected>" . $timezone_identifiers[$i] . "</option>";
               } else {
                 echo "<option>" . $timezone_identifiers[$i] . "</option>";
               }
             }
            ?>
         </select>
      </div>

      <div class="form-group">
        <input type="submit" class="btn btn-default" value="Submit">
      </div>
    </form>
  </div>

  <div id="log-div" class="row">
    <label for="log">Log:</label>
    <div style="width:100%;overflow:auto;" id="log" class="well">
      As you can see, once there's enough text in this box, the box will grow scroll bars... that's why we call it a scroll box! You could also place an image into the scroll box.
    </div>
  </div>
</div>
