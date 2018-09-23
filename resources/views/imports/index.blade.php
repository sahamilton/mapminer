@extends('admin.layouts.default')
@section('content')

<div class="container">
<h2>Import Data</h2>

@foreach ($imports as $import)
<h4><a href="{{route($import.".importfile")}}">Import {{ucwords(str_replace("_"," ",$import))}}</a></h4>
@endforeach

<hr />
<h2>Export Data</h2>

@foreach ($exports as $export)
<h4><a href="{{route(str_replace("_",".",$export).".export")}}">Export {{ucwords(str_replace("_"," ",$export))}}</a></h4>
@endforeach
</div>


@endsection
