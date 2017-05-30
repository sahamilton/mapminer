@extends ('site.layouts.calendar')
@section('content')

<div class="container">
@include('partials.notifications')
<h2>Current Sales Campaigns</h2>
<div class='col-md-offset-2 col-md-8' style="margin-top:20px">
       {!! $calendar->calendar() !!}
    {!! $calendar->script() !!}
</div>
</div>
@endsection