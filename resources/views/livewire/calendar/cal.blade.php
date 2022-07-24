<div>

    <div class="row m-4">
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


            @if(count($team) > 1)
                <x-form-select wire:model='teammember'
                class="flex relative flex-grow items-center px-4 w-full max-w-full"
                name='teammember'
                label='Team:'
                :options="$team" />
            @endif

        </div>
    </div>
    <div
        x-data="{
            calendar: null,
            
            startdate: '{{$startdate}}',
            newEventTitle: null,
            newEventStart: null,
            newEventEnd: null,
            init() {
                this.calendar = new FullCalendar.Calendar(this.$refs.calendar, {
                    
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
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
                        const index = this.getEventIndex(info)

                        this.events[index].start = info.event.startStr
                        this.events[index].end = info.event.endStr
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