@extends ('site.layouts.calendar')
@section('content')

<div class="container">
@include('partials.notifications')
<div class='col-md-offset-2 col-md-8' style="margin-top:20px">
       {!! $calendar->calendar() !!}
    {!! $calendar->script() !!}
</div>
</div>
@endsection