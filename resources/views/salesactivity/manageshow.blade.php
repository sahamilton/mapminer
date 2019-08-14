@extends('admin.layouts.default')
@section('content')
<div class="container">

    <h2>{{$activity->title}}</h2>
     <p><a href="{{route('salesactivity.index')}}">Return to all campaigns</a></p>
     <p>{{$activity->description}}</p>
    <p><b>Date From:</b>{{$activity->datefrom->format('Y-m-d')}}  <b>Date To:</b>{{$activity->dateto->format('Y-m-d')}}</p>
    <p><b>Industry Focus:</b></p>
    @foreach ($activity->vertical as $vertical)
    <li>{{$vertical->filter}}</li>
    @endforeach
    <h4>Participating Branches</h4>
    @include('salesactivity.partials._branches')

</div>
@endsection