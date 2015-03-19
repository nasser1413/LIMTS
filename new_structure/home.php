        <script type="text/javascript">
            $(function() {
                $('#calendar').fullCalendar({
                    theme: true,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    events: 'sections_feed.php',
                    eventClick: function(calEvent, jsEvent, view) {
                        alert('Event: ' + calEvent.title);
                    },
                    eventRender: function(event, element) {
                        element.css('cursor', 'pointer');
                    },
                    weekends: false
                });
            });
        </script>

        <div id='calendar'></div>
