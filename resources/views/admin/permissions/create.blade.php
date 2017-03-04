@extends('admin.layouts.modal')

{{-- Content --}}
@section('content')
	
	{{-- Create permission Form --}}
	<form class="form-horizontal" method="post" action="{{route('admin.permissions.store')}}" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

		<!-- Tabs Content -->
		
			<!-- Tab General -->
			
				<!-- Name -->
				<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="name">Name</label>
                    <div class="col-md-10">
    					<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name') }}}" />
    					{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
                    </div>
				</div>
				<!-- ./ name -->
		
			<!-- ./ tab general -->
			<fieldset><legend>Assigned to Roles</legend>
	        <!-- Permissions tab -->
	         @foreach ($roles as $role)
                <div class="form-group">
                   
                                        
                    <label class="col-md-2 control-label" for="roles[{{{$role['name']}}}]">{{{ $role['name'] }}}</label>
                    <div class="col-md-10">

                        <input class="form-control" type="checkbox" id="roles[{{{ $role['id'] }}}]" name="roles[{{{ $role['id'] }}}]" value="1"{{{ (isset($role['checked']) && $role['checked'] == true ? ' checked="checked"' : '')}}} />
                        
                        </div>

                    
                </div>
@endforeach
</fieldset>


		<!-- Form Actions -->
		<div class="form-group">
            <div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-success">Create permission</button>
            </div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
