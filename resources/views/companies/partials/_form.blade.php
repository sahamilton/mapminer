<div class="form-group @if ($errors->has('type')) has-error @endif">
{{Form::label('companyname','Company Name:')}}

{{Form::text('companyname',isset($company
->companyname) ? $company
->companyname :'',array('class'=>'form-control'))}}
 @if ($errors->has('companyname')) <p class="help-block">{{ $errors->first('companyname') }}</p> @endif
</div>

		<div class="form-group{{ $errors->has('person_id') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">National Account Manager</label>
        <div class="col-md-6">
            <select multiple class="form-control" name='person_id'>

            @foreach ($managers as $key=>$manager)
            	<option 
            	{{(isset($company) && $company->person_id == $key) ? 'selected' : ''}} value="{{$key}}">{{$manager}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('person_id') ? $errors->first('person_id') : ''}}</strong>
                </span>
        </div>
    </div>


<?php $parents=array();?>
		<div class="form-group{{ $errors->has('vertical)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Industry</label>

            <select class="form-control" name='vertical'>

            @foreach ($filters as $vertical))
            	<?php $parent = $vertical->getAncestors()->last();?>
	            	@if(! in_array($parent->id,$parents))
	            	<?php $parents[]=$parent->id;?>
	            	<option disabled value="{{$vertical->id}}">--------{{$parent->filter}}----------</option>
	            	@endif
            		<option value="{{$vertical->id}}">{{$vertical->filter}}</option>

	            	

            @endforeach


            </select>
            <span class="help-block{{ $errors->has('vertical)') ? ' has-error' : '' }}">
                <strong>{{ $errors->has('vertical') ? $errors->first('vertical') : ''}}</strong>
                </span>

    </div>


<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
{{Form::label('ServiceLine','Service Lines:')}}


{{Form::select('serviceline[]',$servicelines,isset($company
) ? $company
->serviceline->pluck('id') : '',array('class'=>'form-control','multiple'=>true))}}

@if ($errors->has('serviceline')) <p class="help-block">{{ $errors->first('serviceline') }}</p> @endif
</div>


<!-- Form Actions -->
	<div style="margin-top:20px">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('company.index') }}">Cancel</a>
			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>