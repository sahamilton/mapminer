@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>User actions for {{$user->person->fullName()}}</h2>
    <p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
    <p>
        <a href="{{route('usertracking.detail', 'Activity')}}"><strong>Activities:</strong>
            {{isset($data['activities']) ? $data['activities']->count() : 0}}
        </a>
    </p>
    <p>
        <strong>Addresses:</strong>
         @if (isset($data['leads']) ? $data['leads']->count() : 0) @endif
    </p>
    <p>
        <strong>Opportunities:</strong>
         @if (isset($data['opportunities']) ? $data['opportunities']->count() : 0) @endif
    </p>
</div>
@endsection