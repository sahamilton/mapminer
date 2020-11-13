@extends('admin.layouts.default')
@section('content')

<div class="container">
    <h2>{{$model}} created by {{$user->person->fullName()}}</h2>
    <p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
    <p><a href="{{route('usertracking.index')}}">Return to all user tracking</a></p>
    @php
    $view = 'admin.users.usertracking.partials._'.strtolower($model);
    @endphp
    @include($view)
</div>
@endsection