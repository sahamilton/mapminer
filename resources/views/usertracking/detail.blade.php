@extends('site.layouts.default')
@section('content')

<div class="container">
    <h2>{{$model}} created by {{$user->person->fullName()}}</h2>
    
    <p><a href="{{route('usertracking.index')}}">Return to all user tracking</a></p>
   @switch ($model) 
    @case ('Opportunity')

        @livewire('usertrack-opportunities', ['period'=>$period, 'user'=>$user])
       @break
    @case('Activity')
        @livewire('usertrack-activities', ['period'=>$period, 'user'=>$user]) 
    @break

    @case ('Address') 
        @livewire('usertrack-activities', ['period'=>$period, 'user'=>$user]) 
    @break

    @case ('Track')
        {{dd("Sorry I havent written that yet');")}}
    @break

    @endswitch

</div>
@endsection