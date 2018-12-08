@extends('site.layouts.default')
@section('content')

<h1>{{$training->title}}</h1>
<p><a href="{{route('training.index')}}">Return to all training videos</a></p>
<h4>{{$training->description}}</h4>
<iframe width="800" height="600" frameborder="0" allowfullscreen="true" style="box-sizing: border-box; margin-bottom:5px; max-width: 100%; border: 1px solid rgba(0,0,0,1); background-color: rgba(255,255,255,0); box-shadow: 0px 2px 4px rgba(0,0,0,0.1);" src="{{$training->reference}}"></iframe>

@include('partials._scripts')
@endsection
