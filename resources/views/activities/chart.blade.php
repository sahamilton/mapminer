@extends('site.layouts.default')
@section('content')
<h2>Edit Activity</h2>
<div class="col-sm-4" style="border:1 solid grey" class="float-right">
  <canvas id="ctx" width="400" height="400" ></canvas>
</div>
@include('activities.partials._mchart')
@include('partials._scripts')
@endsection