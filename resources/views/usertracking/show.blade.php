@extends('site.layouts.default')
@section('content')

<div class="container">
    <h2>User actions for {{$user->person->fullName()}}</h2>
    <p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
    <p><a href="{{route('usertracking.index')}}">Return to all user tracking</a></p>
    <p>
        <a href="{{route('usertracking.detail', 'Activity')}}"><strong>Activities:</strong>
            {{isset($data['Activity']) ? $data['Activity']->count() : 0}}
        </a>
    </p>
    <p>
        <a href="{{route('usertracking.detail', 'Address')}}">
            <strong>Leads:</strong>
                {{$data['Address']->count()}}
        </a>
    </p>
    <p>
        <a href="{{route('usertracking.detail', 'Opportunity')}}">
            <strong>Opportunities:</strong>
                {{$data['Opportunity']->count() }}
        </a>
    </p>
    <p>
        <a href="{{route('usertracking.detail', 'Track')}}">
            <strong>Logins:</strong>
                {{$data['Track']->count() }}</a>
    </p>
</div>
@endsection