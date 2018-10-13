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

    @if(auth()->user()->hasRole('Admin'))
    <h2>All Trainings</h2>
    @else
    <h2>My Trainings</h2>
   @endif

   @if($trainings->count()==0)
        <p class="alert alert-warning">We are sorry {{auth()->user()->person->firstname}}, that there are no trainings based on your role and industry focus. Please contact sales operations.</p>
   @else

                @foreach ($trainings as $training)

                    <h4>{{$training->title}}</h4>
                    <p>{{$training->description}}</p>
                    <a class="viewtraining" href="{{route('training.show',$training->id)}}">

                    </a>

                    <p>
                    @if(auth()->user()->hasRole('Admin'))
                        @foreach ($training->relatedRoles as $role)
                            {{$role->name}}
                            @if(! $loop->last),@endif
                        @endforeach
                    @endif
                    </p>


                @endforeach
    @endif
</div>

@include('partials._scripts')

@endsection
