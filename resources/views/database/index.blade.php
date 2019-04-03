@extends('admin.layouts.default')
@section('content')
	<table id ='sorttable'  class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>Filename</th>
			<th>Date</th>
			<th>Size</th>
			<th>Actions</th>
		</thead>
		<tbody>
		@foreach($backups as $file)
			
			<tr> 
				<td>{{$file->getfilename()}}</td>
				<td>{{date('Y-m-d g:i a',$file->getaTime())}}</td>
				<td>{{number_format($file->getsize()/1000,0)}} kB</td>
				<td>
				<div class="btn-group">
					<button type="button" 
						class="btn btn-success dropdown-toggle" 
						data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					</button>
					<ul class="dropdown-menu" role="menu">

						<a class="dropdown-item"
						href="{{$file->getlinkTarget()}}">
						<i class="far fa-edit text-info" 
						aria-hidden="true"> </i>

						Download Backup 
						</a>

						<a class="dropdown-item"
						title="Delete backup"
						  data-href="{{route('database.destroy',$file->getfilename())}}" 
						  data-toggle="modal" 
						  data-target="#confirm-delete" 
						  data-title = "this backup" 
						  href="#">
						  <i class="far fa-trash-alt text-danger" 
						    aria-hidden="true"> </i>
						   Delete backup
						</a>
					</ul>
					</div>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@include('partials._modal')
@include('partials._scripts')
@endsection