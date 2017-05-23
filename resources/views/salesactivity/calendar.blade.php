@extends ('site.layouts.calendar')
@section('content')

<div class="container">
@include('partials.notifications')
<div class='col-md-offset-2 col-md-8'>
       {!! $calendar->calendar() !!}
    {!! $calendar->script() !!}
</div>
</div>
@endsection