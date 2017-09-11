@extends('admin.layouts.default')
@section('content')

<div class="container">
<h2>Import Data</h2>

@foreach ($imports as $import)
<h4><a href="{{route($import.".importfile")}}">Import {{ucfirst($import)}}</a></h4>
@endforeach
</div>


@stop
