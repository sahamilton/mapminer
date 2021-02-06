@extends('site.layouts.default')
@section('content')



<p><a href="{{route('branchdashboard.show', $branch->id)}}">
<i class="fas fa-tachometer-alt"></i>
 Return To Branch Dashboard</a></p>


@livewire('activities-table', ['branch'=>$branch->id, 'myBranches'=>$myBranches])
@include('partials._scripts')

@endsection