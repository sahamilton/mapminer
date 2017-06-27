@extends('admin.layouts.default')

@if($errors->any())

<h4>{{$errors->first()}}</h4>
@endif
<?php $actions = ['Replace','Add / Edit'];?>
{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			Import Users</h3>

			
	</div>

<h2>Steps to import branches</h2>
<ol>
<li>First create your csv file of branches from the template.  Do not change, add or delete any field / column</li>
<li>Save the CSV file locally on your computer.</li>
<li>Determine if you want to completely erase and reimport the list or just make adds & edit from the import list</li>
<ul><li>Purge will delete all branches and reimport from theh spreadsheet</li>
<li>Edits will delete the branch based on branch muber and reimport all the data for that one branch</li>
<li>To delete a single branch use the regular branch listing and the green actions button</li></ul>

<li>Select the file and import</li>

</ol>
<div>
{{ Form::open(array('route'=>'branches.bulkimport', 'files' => true)) }}
<div class='row'>
<div class="col-md-4">
<!-- Service Lines -->
				<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
					{{Form::label('ServiceLine','Service Lines:', array('class'=>"col-md-2 control-label"))}}

<div class="col-md-6">
					{{Form::select('serviceline[]',$servicelines,isset($user) ? $user->serviceline->pluck('id') :'',array('class'=>'form-control','multiple'=>true))}}

					@if ($errors->has('serviceline')) <p class="help-block">{{ $errors->first('serviceline') }}</p> @endif
					</div></div>
				<!-- ./ servicelines -->

</div>
</div>
<div>
{{Form::file('upload')}}
{{ $errors->first('upload') }}
</div></div>
<div>
{{Form::label('Import Type')}}

@foreach ($actions as $action)
{{ Form::radio('type', $action)}}{{ $action}}
@endforeach
</div>
<div>
{{Form::submit('Import Branches',['class' => 'btn btn-sm btn-success'])}}
</div>
{{Form::close()}}

@stop	
    
@include('partials/_scripts')
@stop
