<div>
{{$startdate}}
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

      </div>
    </div>
    <div
        x-data="{
            calendar: null,
            events: {{json_encode($events)}},
            startdate: '{{$startdate}}',
            newEventTitle: null,
            newEventStart: null,
            newEventEnd: null,
            init() {
                this.calendar = new FullCalendar.Calendar(this.$refs.calendar, {
                    events: (info, success) => success(this.events),
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
                    
                    eventChange: (info) => {
                        const index = this.getEventIndex(info)

                        this.events[index].start = info.event.startStr
                        this.events[index].end = info.event.endStr
                    },
                })

                this.calendar.render()
            },
            getEventIndex(info) {
                return this.events.findIndex((event) => event.id == info.event.id)
            },
            
        }"
    >
        <div x-ref="calendar"></div>
    </div>
   
</div>