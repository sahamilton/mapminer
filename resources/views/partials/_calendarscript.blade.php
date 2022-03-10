<script>
  $(document).ready(function () {
         
        var SITEURL = "{{url('/')}}";
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
 
        var calendar = $('#calendar').fullCalendar({
                headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            
            },
            eventLimit: true,
            events: "{{route('calendar.index',$data['period']['period'])}}",
            displayEventTime: false,
            editable: false,
            droppable: true,
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    element.allDay = true;
                } else {
                    element.allDay = false;
                }
                if (event.completed === '1') {
                    
                     element.css("background-color", "#E77C22");
                   
                } else {
                     element.css("background-color", "#3B3B62");
                     element.css("color", "#FFFFFF");
                    
                }
                event.eventClick = event.route;

                     
            },
            
             
            
 
        });
  });
 
</script>