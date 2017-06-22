<?php isset($company[0]->managedBy->id) ? $managedBy = $company[0]->managedBy->id : $managedBy = "";?>
<div class="form-group @if ($errors->has('type')) has-error @endif">
{{Form::label('companyname','Company Name:')}}

{{Form::text('companyname',isset($company[0]->companyname) ? $company[0]->companyname :'',array('class'=>'form-control'))}}
 @if ($errors->has('companyname')) <p class="help-block">{{ $errors->first('companyname') }}</p> @endif
</div>


<div class="form-group @if ($errors->has('user_id')) has-error @endif">
{{Form::label('Manager','National Account Manager:')}}
<div class="controls">
{{Form::select('user_id',$managers,$managedBy,array('class'=>'form-control'))}}
 @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
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
            <span class="help-block">
                <strong>{{ $errors->has('vertical') ? $errors->first('vertical') : ''}}</strong>
                </span>

    </div>


<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
{{Form::label('ServiceLine','Service Lines:')}}


{{Form::select('serviceline[]',$servicelines,isset($company[0]) ? $company[0]->serviceline->pluck('id') : '',array('class'=>'form-control','multiple'=>true))}}

@if ($errors->has('serviceline')) <p class="help-block">{{ $errors->first('serviceline') }}</p> @endif
</div>


<!-- Form Actions -->
	<div style="margin-top:20px">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('company.index') }}">Cancel</a>
			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>