@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>User actions for {{$user->person->fullName()}}</h2>
<p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
@include('admin.users.usertracking.form')
<p><strong>Activities:</strong>{{$data['activities']->count()}}</p>
<p><strong>Addresses:</strong>{{$data['leads']->count()}}</p>
<p><strong>Opportunities:</strong>{{$data['opportunities']->count()}}</p>
</div>
@endsection