@extends('site/layouts/default')
@section('content')
<div class="container">
    <h2>My Trainings</h2>
   

                @foreach ($trainings as $training)

                    <h4>{{$training->title}}</h4>
                    <p>{{$training->description}}</p>
<iframe width="400" height="300" frameborder="0" allowfullscreen="true" style="box-sizing: border-box; margin-bottom:5px; max-width: 100%; border: 1px solid rgba(0,0,0,1); background-color: rgba(255,255,255,0); box-shadow: 0px 2px 4px rgba(0,0,0,0.1);" src="{{$training->reference}}"></iframe>
                  
                @endforeach

@include('partials._scripts')
@endsection
