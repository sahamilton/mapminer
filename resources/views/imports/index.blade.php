@extends('admin.layouts.default')
@section('content')

<div class="container">
<h4> Import / Export Data </h4>



<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
    	<i class="fas fa-upload"></i> Imports</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
    	<i class="fas fa-download"></i> Exports</a>
  </li>
  
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  	<h2>Import Data</h2>

			@foreach ($imports as $import)
			<h4><a href="{{route($import.".importfile")}}">Import {{ucwords(str_replace("_"," ",$import))}}</a></h4>
			@endforeach
</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  	<h2>Export Data</h2>

			@foreach ($exports as $export)
			<h4><a href="{{route(str_replace("_",".",$export).".export")}}">Export {{ucwords(str_replace("_"," ",$export))}}</a></h4>
			@endforeach
		</div>
  
</div>
</div>


@endsection
