@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$import->ref}} Imports</h2>
	<p></a>
	
		
		<div id="branches" >
			<form name="assigntoBranch" method="post" action="{{route('fileimport.assign',$import->id)}}">
			  <div class="form-group">
			  	<input type="submit" name="assign" class="btn btn-info" value="Assign to branches">	
			  </div>
				<div class="form-group">
		            <label class="checkbox-inline " for="password_confirmation">Notify Managers</label>
		           
		              <input 
		                class="checkbox-inline " 
		                type="checkbox" 
		                name="notify" 
		                id="notify" 
		                value="1"/>
		          </div>
            
				@csrf
				
			</form>
		</div>
		


@include('partials._scripts')
@endsection