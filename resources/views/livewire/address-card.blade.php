<div>
    @wire
    <h2>{{$address->businessname}}</h2>
    <h4>{{ucwords($view)}}</h4>
    <x-form-select 
        name='view' 
        label="View:" 
        :options="$viewtypes" 
    />

    @switch ($view)
        @case('summary')
            @include('addresses.partials._lwdetails')
        @break;
        @case('contacts')
            @include('addresses.partials._lwcontacts')
        @break;
        @case('activities')
            @include('addresses.partials._lwactivities')
        @break;
        @case('opportunities')
            @include('addresses.partials._lwopportunities')
        @break;
    @endswitch
    @endwire
</div>
