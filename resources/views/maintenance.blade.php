@extends('site.layouts.default')
@section('content')

<div class="jumbotron">
  <div class="container" style="position:relative;text-align:center">
    <h4>Welcome to Mapminer&reg; </h4>
<div id="welcome">


@if(auth()->check()) 

<div id="accountbtn" style="text-align:left; padding-left:20%;padding-top:100px">
<a href='company'class='btn btn-lg btn-primary' title='Search for specific accounts'>Account Views</a>


</div>


<div id="branchbtn" style="text-align:left; padding-left:70%;padding-top:50px">
<a href='branch'class='btn btn-lg btn-warning' title='Explore Branches and their national account locations'>Branch Views</a>


</div>
<div id="peoplebtn" style="text-align:left; padding-left:50%;padding-top:100px">
<a href='person'class='btn btn-lg btn-success' title='Search for people'>People Views</a>


</div>

@else
<div>
<img src="{{asset('/assets/images/maintenance.jpg')}}" width="266" height="189" alt="Down for maintenance" />

</div>
@endif
</div>
  </div>
</div>

@endsection
