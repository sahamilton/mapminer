@extends('site/layouts/default')
@section('content')
<div class="container">
<h2>{{$manager->fullName()}}'s Accounts Dashboard</h2>
@include('managers.partials._accountselector')


</div>
@include('partials._scripts')


@endsection
