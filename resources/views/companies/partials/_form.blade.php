

<!-- company -->
    <div class="form-group{{ $errors->has('companyname') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Company Name:</label>
           <div class="input-group input-group-lg ">
                <input required type="text" 
                class="form-control" 
                name='companyname' 
                description="company" 
                value="{!! old('companyname', isset($company) ? $company->companyname : "") !!}" 
                placeholder="companyname">
                <span class="help-block">
                    <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
                    </span>
            </div>
    </div>

<!-- national account manager -->
        <div class="form-group{{ $errors->has('person_id)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">National Account Manager:</label>
           <div class="input-group input-group-lg ">
            <select  class="form-control" name='person_id'>
                <option value=''>None Assigned</option>
            @foreach ($managers as $manager)
                <option @if(isset($company) && $company->person_id == $manager->id)
                    selected 
@endif
                value="{{$manager->id}}">{{$manager->fullName()}}
              

                
            </option>
            @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('person_id') ? $errors->first('person_id') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- Company Type -->

<div class="form-group{{ $errors->has('accounttypes_id)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Company Type:</label>
           <div class="input-group input-group-lg ">
            <select  required class="form-control" name='accounttypes_id'>
                <option value=null>Not Assigned</option>
            @foreach ($types as $type)
                <option @if(isset($company) && $company->accounttypes_id == $type->id)
                    selected 
            @endif
                value="{{$type->id}}">{{$type->type}}
              

                
            </option>
            @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('accounttypes_id') ? $errors->first('accounttypes_id') : ''}}</strong>
                </span>
        </div>
    </div>
@include('companies.partials._verticalselector')


<!-- Serviceline -->
@if($servicelines->count()>1)
        <div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Service Lines:</label>
        <div class="input-group input-group-lg ">
            <select multiple class="form-control" name='serviceline[]'>

            @foreach ($servicelines as $key=>$serviceline))
                <option  @if(isset($company) && in_array($key,$company->serviceline->pluck('id')->toArray())) selected @endif value="{{$key}}">{{$serviceline}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}</strong>
                </span>
        </div>
    </div>
@else
<input type="hidden" name="serviceline[]" value="{{key($servicelines->toArray())}}" />

@endif
<!-- company -->
    <div class="form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Customer Number:</label>
           <div class="input-group input-group-lg ">
                <input type="text" 
                class="form-control" 
                name='customer_id' 
                description="Customer Number" 
                value="{!! old('companyname', isset($company) ? $company->customer_id : "") !!}" 
                placeholder="Customer Number">
                <span class="help-block">
                    <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
                    </span>
            </div>
    </div>
@include('partials._verticalsscript') 
