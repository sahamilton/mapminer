@extends('frontend.layouts.default')
@section('content')

<h4>{{$people->firstname}} {{$people->lastname}} </h4>
<p>{{$people->email}}</p>

@endsection
