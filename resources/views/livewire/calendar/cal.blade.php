<div>
    @include('notifications')

    <div class="row m-4">
        <div class="col form-inline">
            @if(count($team) > 1)
                <x-form-select wire:model='teammember'
                class="flex relative flex-grow items-center px-4 w-full max-w-full"
                name='teammember'
                label='Team:'
                :options="$team" />
            @endif
        </div>
        


    </div>
    <div class="row m-4">
      
        <div class='col form-inline mx-4'>
            @foreach ($activitytypes as $acttype)
               <a href="#" wire:click.prevent="changeType({{$acttype->id}})">
                <span class="rounded mx-2" style="padding: 4px;border: {{$type == $acttype->id ? '3px': '1px'}} solid #{{$acttype->color}};background-color:#eeeeee">
                    <i class="fa-solid fa-circle-small" style="color:#{{$acttype->color}}"></i>
                    {{$acttype->activity}}
                </span> 
                </a> |
            @endforeach
            
            |<a href="#" wire:click.prevent="changeStatus(1)">
            <span class="rounded mx-2" style="padding: 4px;background-color:#aceeca;border: {{$status == 1 ? '3px': '1px'}} solid #999999">Completed</span></a> |
            <a href="#" wire:click.prevent="changeStatus(2)">
            <span class="rounded mx-2" style="padding: 4px;background-color:#ffc04d;border: {{$status == 2 ? '3px': '1px'}} solid #999999">To Do</span></a>
        </div>
    </div>
    <div class="row">
        <div class="col offset-1">
            
            <a href="{{route('upcomingactivity.branch',$branch_id)}}">
                Upcoming Activities
            </a>
       </div>
       <div class="col">
        
            <i class="fa-solid fa-calendar-check txt-success"></i>
            <a href="{{route('ical', auth()->user()->id)}}">
                Download this weeks upcoming events to your Outlook calendar
            </a>

        </div>
    </div>
    
    <div
        x-data="{
            calendar: null,
            
            startdate: '{{isset($startdate) ? $startdate : now()->startOfMonth()}}',
            newEventTitle: null,
            newEventStart: null,
            newEventEnd: null,
            init() {
                this.calendar = new FullCalendar.Calendar(this.$refs.calendar, {
                    
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    },
                    dayMaxEventRows: true,
                    initialDate: this.startdate,
                    initialView: 'dayGridMonth',
                    selectable: true,
                    unselectAuto: false,
                    editable: true,
                    select: (info) => {
                        this.newEventStart = info.startStr
                        this.newEventEnd = info.endStr
                    },
                    dateClick: (info) =>{
                        
                        this.calendar.changeView('timeGridDay', info.dateStr);
                        
                    },
                    eventChange: (info) => {
                                               
                        @this.eventDrop(info.event)
                    },
                })
                this.calendar.addEventSource( {
                    url: '/calendar',
                    extraParams: function() {
                        return {
                            branch: @this.branch_id,
                            status: @this.status,
                            type: @this.type,
                            team: @this.teammember,
                        };
                    }
                })

                this.calendar.render()

                @this.on(`refreshCalendar`, () => {
                    
                    this.calendar.refetchEvents()
                })
            },
            getEventIndex(info) {
                return this.events.findIndex((event) => event.id == info.event.id)
            },
            
        }"
    >
        <div x-ref="calendar" wire:ignore></div>
    </div>
   
</div>