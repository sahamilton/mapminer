@extends('site/layouts/default')
@section('content')
<style>
a.viewtraining{
	display: block;
	width:400px;
	height:300px;
	background-image: url("/assets/images/viewimage.png");
	background-repeat:no-repeat;

}
</style>
<div class="container">
    <h2>My Trainings</h2>
   

                @foreach ($trainings as $training)

                    <h4>{{$training->title}}</h4>
                    <p>{{$training->description}}</p>
                    <a class="viewtraining" href="{{route('training.show',$training->id)}}">

                    </a>

                  
                @endforeach

@include('partials._scripts')
@endsection
