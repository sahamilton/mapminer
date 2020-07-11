<script>
  $(document).ready(function () {
         
        var SITEURL = "{{url('/')}}";
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
 
        var calendar = $('#calendar').fullCalendar({
            eventLimit: true,
            events: "{{route('calendar.index',$data['period']['period'])}}",
            displayEventTime: true,
            editable: true,
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