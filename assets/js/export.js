var CLIENT_ID = '1007287381682-kigjem9l3mub4q97vapghj46o40i7fbo.apps.googleusercontent.com';
var SCOPES = ["https://www.googleapis.com/auth/calendar"];

var calendarApiLoaded = false,
    $log;

$(function() {
  $log = $("#log");
});

function checkAuth() {
  gapi.auth.authorize(
    {
      'client_id': CLIENT_ID,
      'scope': SCOPES,
      'immediate': true
    }, handleAuthResult);
}

function handleAuthResult(authResult) {
  var $authorizeDiv = $("#authorize-div");

  if (authResult && !authResult.error) {
    // Hide auth UI, then load client library.
    $authorizeDiv.css('display', 'none');
    loadCalendarApi();
  } else {
    // Show auth UI, allowing the user to initiate authorization by
    // clicking authorize button.
    $authorizeDiv.css('display', 'inline');
  }
}

function handleAuthClick(event) {
  gapi.auth.authorize(
    {client_id: CLIENT_ID, scope: SCOPES, immediate: false},
    handleAuthResult);
  return false;
}

function loadCalendarApi() {
  gapi.client.load('calendar', 'v3', onCalendarApiLoaded);
}

function loadEvents(data) {
  $.ajax({
    url: "getters/sections_feed.php",
    cache: false,
    data: data,
    dataType: "json",
    success: function(feed) {
      onEventsLoaded(feed.events);
    }
  });
}

function onCalendarApiLoaded() {
  calendarApiLoaded = true;

  $("#form-div").show();

  loadSelector("semester", undefined, {
    multiselect: false
  });

  loadSelector("professor", undefined, {
    multiselect: false
  });
}

function onFormSubmitted() {
  var semesterId = $("#semester-selector").find("option:selected").val(),
      professorId = $("#professor-selector").find("option:selected").val();

  ajaxLoadJSON("semester", function(i, semester) {
    var start = moment.unix(semester.start).format(),
        end = moment.unix(semester.end).format();

    loadEvents({
      start: start,
      end: end,
      professor: professorId,
      semester: semesterId
    });
  }, {
    id: semesterId
  });
}

function onEventsLoaded(events) {
  var timezone = $("#timezone-selector").find("option:selected").text();

  for (var i = 0; i < events.length; i++) {
    var rawEvent = events[i];
    var event = {
      'summary': rawEvent.title,
      'location': rawEvent.location,
      'description': rawEvent.description,
      'start': {
        'dateTime': rawEvent.start,
        'timeZone': timezone
      },
      'end': {
        'dateTime': rawEvent.end,
        'timeZone': timezone
      }
    };

    var request = gapi.client.calendar.events.insert({
      'calendarId': 'primary',
      'resource': event
    });

    request.execute(function(event) {
      appendToLog('Event created: ' + JSON.stringify(event));
    });
  }
}

function appendToLog(text) {
  $log.append(text + "</br>");
}
