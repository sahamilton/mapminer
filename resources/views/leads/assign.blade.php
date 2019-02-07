@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$lead->businessname}} Prospect</h2>
	<p><a href="{{route('address.show',$lead->id)}}" target="_blank">Review Prospect</a>
	
		
		<div id="branches" >
			<form name="assigntoBranch" method="post" action="{{route('webleads.assign')}}">
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
				
					@include('leads.partials._branchlist')
				<input type="hidden" name="address_id" value="{{$lead->id}}" />
			</form>
		</div>
		


@include('partials._scripts')
@endsection