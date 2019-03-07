@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$leadsource->sourcename}} Leads</h2>

  <fieldset><legend>Assign Geographically</legend>
	<p><a href="{{route('leadsource.index')}}">Return to all prospect sources</a></p>
	<form name="bulkassign" method="post" action="{{route('leads.geoassign', $leadsource->id)}}" >
		@csrf()
    <!-- assign based on industry -->

    <!-- roles to assign to -->
    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label"><strong>Assign to Roles</strong></label>
        <div class="col-md-6">
            
          <input type="radio" checked name="type" value="role">
            <span class="help-block">
                <strong>{{ $errors->has('type') ? $errors->first('type') : ''}}</strong>
            </span>
        </div>
    </div>
    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Select Roles:</label>
        <div class="col-md-6">
            <select name="roles[]" multiple class="form-control" >
                @foreach ($leadroles as $id=>$role)
                    <option value="{{$id}}">{{$role}}</option>
                @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('roles') ? $errors->first('roles') : ''}}</strong>
            </span>
        </div>
    </div>
    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label"><strong>Assign to Branches</strong></label>
        <div class="col-md-6">
            
          <input type="radio" name="type" value="branch">
            <span class="help-block">
                <strong>{{ $errors->has('type') ? $errors->first('type') : ''}}</strong>
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
	    
</fieldset>

<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label"><strong>Assign to Specific Branches</strong></label>
        <div class="col-md-6">
          <input type="radio" name="type" value="specific">
            <span class="help-block">
                <strong>{{ $errors->has('type') ? $errors->first('type') : ''}}</strong>
            </span>
        </div>
    </div>
<div class="form-group{{ $errors->has('branches)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Select Branches:</label>
        <div class="col-md-6">
            <select multiple id="branch" class="form-control" name='branch[]'>
                @foreach($branches as $branch)
                    <option value="{{$branch->id}}">{{$branch->branchname}}</option>
                @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('type') ? $errors->first('type') : ''}}</strong>
                </span>
        </div>
        
</div>


  </fieldset>
  <input type="submit" name="submit" value="Assign Leads" class="btn btn-info">
  </form>

@include('partials._scripts')
@endsection