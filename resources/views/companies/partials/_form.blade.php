<!-- company -->
    <div class="form-group{{ $errors->has('companyname') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Company Name:</label>
           <div class="input-group input-group-lg ">
                <input required type="text" class="form-control" name='companyname' description="company" value="{{ old('companyname') ?  old('companyname') : isset($company) ? $company->companyname : "" }}" placeholder="companyname">
                <span class="help-block">
                    <strong>{{ $errors->has('companyname') ? $errors->first('companyname') : ''}}</strong>
                    </span>
            </div>
    </div>

<!-- national account manager -->
        <div class="form-group{{ $errors->has('person_id)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">National Account Manager:</label>
           <div class="input-group input-group-lg ">
            <select  required class="form-control" name='person_id'>
            @foreach ($managers as $manager)
                <option @if(isset($company) && $company->person_id == $manager->id) selected @endif
                value="{{$manager->id}}">{{$manager->fullname}}</option>
            @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('person_id') ? $errors->first('person_id') : ''}}</strong>
                </span>
        </div>
    </div>



<?php $parents=array();?>
		<div class="form-group{{ $errors->has('vertical)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Industry:</label>
         <div class="input-group input-group-lg ">
            <select required class="form-control" name='vertical'>

            @foreach ($filters as $vertical))
            	
	            	@if(! in_array($vertical->parent_id,$parents) && null!==$filters->get($vertical->parent_id))
                    <?php $parents[]=$vertical->parent_id;
                    $parent = $filters->get($vertical->parent_id);?>
                    
                    @if(! $loop->first)
                        </optgroup>
                    @endif
	            	<optgroup label= "{{$parent->filter}}" 

	            	@endif

            		<option @if(isset($company) && $company->vertical == $vertical->id) selected @endif value="{{$vertical->id}}">{{$vertical->filter}}</option>

	            	

            @endforeach
            </optgroup>

            </select>
            <span class="help-block{{ $errors->has('vertical)') ? ' has-error' : '' }}">
                <strong>{{ $errors->has('vertical') ? $errors->first('vertical') : ''}}</strong>
                </span>
                </div>
    </div>
<!-- Serviceline -->

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
