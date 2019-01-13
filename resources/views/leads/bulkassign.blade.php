@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$leadsource->sourcename}} Prospect Geographically</h2>
	<p><a href="{{route('leadsource.index')}}">Return to all prospect sources</a></p>
	<form name="bulkassign" method="post" action="{{route('leads.geoassign', $leadsource->id)}}" >
		@csrf()
    <!-- assign based on industry -->

    <!-- roles to assign to -->
    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Assign to Roles:</label>
        <div class="col-md-6">
            <select name="roles[]" required multiple class="form-control" >
                @foreach ($leadroles as $key=>$role)
                    <option value="{{$role}}">{{$role}}</option>
                @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('roles') ? $errors->first('roles') : ''}}</strong>
            </span>
        </div>
    </div>
    <!-- limit to assign -->
    <div class="form-group{{ $errors->has('limit') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Assign to max </label>
              <div class="col-md-2">
                  <input 
                  type="number" 
                  min="1" 
                  step="1"
                  class="form-control" 
                  name='limit' 
                  required
                  description="limit" 
                  value="5" 
                  placeholder="assing to number of people"> people
                  <span class="help-block">
                      <strong>{{ $errors->has('limit') ? $errors->first('limit') : ''}}</strong>
                      </span>
              </div>
      </div>
      <!-- /limit -->

      <!-- distance -->

        <div class="form-group{{ $errors->has('distance') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Max distance</label>
            <div class="col-md-2">
                <input 
                type="number"
                min="10"
                required
                class="form-control" 
                name='distance' 
                description="distance" 
                value="25" 
                placeholder="distance"> miles
                <span class="help-block">
                    <strong>
                        {{ $errors->has('distance') ? $errors->first('distance') : ''}}
                    </strong>
                </span>
            </div>
        </div>
	    <input type="submit" name="submit" value="Assign Geographically" class="btn btn-info">
	</form>


@include('partials._scripts')
@endsection