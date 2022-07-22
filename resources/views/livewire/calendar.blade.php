<div>
  

    
  <div class="row mb-4">
    <div class="col form-inline">
      <x-form-select wire:model='type'
        class="flex relative flex-grow items-center px-4 w-full max-w-full"
        name='type'
        label='Select type:'
        :options="$types" />

      <x-form-select wire:model='status'
        class="flex relative flex-grow items-center px-4 w-full max-w-full"
        name='status'
        label='Status:'
        :options="$statuses" />

      </div>
    </div>
  
   
    
  @push('scripts')

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.js'></script>
    
    <script>
    document.addEventListener('livewire:load', function() {
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendar.Draggable;

        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');
        var checkbox = document.getElementById('drop-remove');

        // initialize the external events
        // -----------------------------------------------------------------

        new Draggable(containerEl, {
        itemSelector: '.fc-event'
        });

        // initialize the calendar
        // -----------------------------------------------------------------

        var calendar = new Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        dayMaxEventRows: true,
        views: {
          timeGrid: {
            dayMaxEventRows: 6 // adjust to 6 only for timeGridWeek/timeGridDay
          }
        },
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        drop: function(info) {
            // is the "remove after drop" checkbox checked?
            if (checkbox.checked) {
            // if so, remove the element from the "Draggable Events" list
            info.draggedEl.parentNode.removeChild(info.draggedEl);
            }
        },
        eventReceive: info => @this.eventReceive(info.event),
        eventDrop: info => @this.eventDrop(info.event, info.oldEvent),
        loading: function(isLoading) {
                if (!isLoading) {
                    // Reset custom events
                    this.getEvents().forEach(function(e){
                        if (e.source === null) {
                            e.remove();
                        }
                    });
                }
            }
        });

        calendar.addEventSource( {
            url: '/calendar',
            extraParams: function() {
                return {
                    branch: @this.branch_id,
                    status: @this.status,
                    type: @this.type
                };
            }
        });

        calendar.render();

        @this.on(`refreshCalendar`, () => {
            calendar.refetchEvents()
        });
    });

    </script>


    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.css' rel='stylesheet' />

    <style>

    html, body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px;
    }
  </style>

  @endpush
</div>