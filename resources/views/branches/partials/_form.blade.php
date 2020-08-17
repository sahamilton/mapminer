@php
$state = new App\State;
$states = $state->getStates();

@endphp
<div class="container" style="margin-top:40px">
<!-- id -->
<div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Branch Number:</label>
    <div class="input-group input-group-lg">
        <input 
        @if(isset($branch)) readonly @endif
        type="text" class="form-control" name='id' description="id" 
        value="{{ old('id', isset($branch) ? $branch->id :'' ) }}" 
        placeholder="id">
        <span class="help-block">
            <strong>{{ $errors->has('id') ? $errors->first('id') : ''}}</strong>
        </span>
    </div>
</div>


<!-- branchname -->
<div class="form-group{{ $errors->has('branchname') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Branch Name</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='branchname' description="branchname" 
        value="{{ old('branchname', isset($branch) && isset($branch->branchname) ? $branch->branchname : '') }}" 
        placeholder="branchname">
        <span class="help-block">
            <strong>{{ $errors->has('branchname') ? $errors->first('branchname') : ''}}</strong>
        </span>
    </div>
</div>

<!-- street -->
<div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Address:</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='street' description="street" 
        value="{{ old('street' , isset($branch) && isset($branch->street) ? $branch->street : '') }}" 
        placeholder="street">
        <span class="help-block">
            <strong>{{ $errors->has('street') ? $errors->first('street') : ''}}</strong>
        </span>
    </div>
</div>





<!-- address2 -->
<div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Suite/Unit:</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='address2' description="address2" 
        value="{{ old('address2', isset($branch) && isset($branch->address2) ? $branch->address2 : '' ) }}" 
        placeholder="address2">
        <span class="help-block">
            <strong>{{ $errors->has('address2') ? $errors->first('address2') : ''}}</strong>
        </span>
    </div>
</div>



<!-- city -->
<div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">City:</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='city' description="city" 
        value="{{ old('city' , isset($branch) && isset($branch->city) ? $branch->city : '') }}" 
        placeholder="city">
        <span class="help-block">
            <strong>{{ $errors->has('city') ? $errors->first('city') : ''}}</strong>
        </span>
    </div>
</div>


<div class="form-group{{ $errors->has('state)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">States:</label>
    <div class="input-group input-group-lg">
        <select  class="form-control" name='state'>
        @foreach ($states as $key=>$state))
            <option @if (isset($branch) && $branch->state == $key) selected @endif value="{{$key}}">{{$state}}</option>
        @endforeach
        </select>
        <span class="help-block">
            <strong>{{ $errors->has('state') ? $errors->first('state') : ''}}</strong>
        </span>
    </div>
</div>

<!-- zip -->
<div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">ZIP / Postal Code:</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='zip' description="zip" 
        value="{{ old('zip', isset($branch) && isset($branch->zip) ? $branch->zip : '' ) }}" 
        placeholder="zip">
        <span class="help-block">
            <strong>{{ $errors->has('zip') ? $errors->first('zip') : ''}}</strong>
        </span>
    </div>
</div>




<!-- radius -->
<div class="form-group{{ $errors->has('radius') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Service Radius in miles:</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='radius' description="radius" 
        value="{{ old('radius' , isset($branch) && isset($branch->radius) ? $branch->radius : "25" )}}" 
        placeholder="service radius">
        <span class="help-block">
            <strong>{{ $errors->has('radius') ? $errors->first('radius') : ''}}</strong>
        </span>
    </div>
</div>

<div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Servicelines</label>
    <div class="input-group input-group-lg">
        <select multiple required class="form-control" name='serviceline[]'>
            @foreach ($servicelines as $serviceline))
                <option @if(isset($branch) && in_array($serviceline->id,$branchservicelines)) selected @endif value="{{$serviceline->id}}">{{$serviceline->ServiceLine}}</option>
            @endforeach
        </select>
        <span class="help-block">
            <strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}</strong>
        </span>
    </div>
</div>

<?php $regions = [ '1'=>'Western' ,'2'=>'CLP','3'=>'Eastern','4'=>'Mid-America & Canada
'];?>
<div class="form-group{{ $errors->has('region_id)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Region:</label>
    <div class="input-group input-group-lg">
        <select  class="form-control" name='region_id'>
            @foreach ($regions as $key=>$region))

                <option @if(isset($branch) && $branch->region_id == $key) selected @endif value="{{$key}}">{{$region}}</option>
            @endforeach
        </select>
        <span class="help-block">
            <strong>{{ $errors->has('region_id') ? $errors->first('region_id') : ''}}</strong>
        </span>
    </div>
</div>

</div>






