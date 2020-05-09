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
            events: "{{route('cal.month',$data['period']['period'])}}",
            displayEventTime: true,
            editable: true,
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    element.allDay = true;
                } else {
                    element.allDay = false;
                }
                if (event.completed == 1) {
                    element.addClass('blue-background');
                   
                } else {
                    element.addClass('red-background');
                    
                }

                     
            },
            
             
            
 
        });
  });
 
</script>