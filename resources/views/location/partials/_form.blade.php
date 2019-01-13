<?php 

// get segment and business type options
$state = new App\State;
   $states = $state->getStates();

if(isset($location->location->company->vertical)){
$segments = App\SearchFilter::where('parent_id','=',$location->location->company->vertical)
			->orWhere(function($query){ 
					$query->where('searchcolumn','=','segment')
					->where('canbenull','=',1);})
			->pluck('filter','id');
}
$nullSegment = App\SearchFilter::where('searchtable','=','locations')
			->where('searchcolumn','=','segment')
			->where('canbenull','=',1)
			->pluck('id');

$businesstypes = App\SearchFilter::where('searchtable','=','locations')
			->where('searchcolumn','=','businesstype')
			->where('type','!=','group')
			->pluck('filter','id');
			
$nullBusinesstype = App\SearchFilter::where('searchtable','=','locations')
			->where('searchcolumn','=','businesstype')
			->where('canbenull','=',1)
			->pluck('id');
?>

<!--- Business Name -->
<div class="form-group @if ($errors->has('businessname')) has-error @endif">
{{Form::label('businessname','Business Name:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('businessname')}}

@if ($errors->has('businessname')) <p class="help-block">{{ $errors->first('businessname') }}</p> @endif
</div>

<!--- Address -->
<div class="form-group @if ($errors->has('address')) has-error @endif">
{{Form::label('street','Address:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('street')}}

@if ($errors->has('street')) <p class="help-block">{{ $errors->first('street') }}</p> @endif
</div>

<!--- Suite -->
<div class="form-group @if ($errors->has('address2')) has-error @endif">
{{Form::label('suite','Suite:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('address2')}}

@if ($errors->has('address2')) <p class="help-block">{{ $errors->first('address2') }}</p> @endif
</div>

<!--- City -->
<div class="form-group @if ($errors->has('city')) has-error @endif">
{{Form::label('city','City:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('city')}}

@if ($errors->has('city')) <p class="help-block">{{ $errors->first('city') }}</p> @endif
</div>


<!--- State -->
<div class="form-group @if ($errors->has('state')) has-error @endif">
	<label class="control-label col-md-2">State:</label>
	<select name="state" >
		@foreach ($states as $key=>$state)
		
		<option 
		@if($location && $location->location->state == $key)
			selected
		@endif

		value="{{$key}}">{{$state}}</option>
		@endforeach

	</select>

@if ($errors->has('state')) <p class="help-block">{{ $errors->first('state') }}</p> @endif
</div>


<!--- ZIP -->
<div class="form-group @if ($errors->has('zip')) has-error @endif">
{{Form::label('zip','Zip / Postal Code:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('zip')}}

@if ($errors->has('zip')) <p class="help-block">{{ $errors->first('zip') }}</p> @endif
</div>

<!--- Phone -->
<div class="form-group @if ($errors->has('phone')) has-error @endif">
{{Form::label('phone','Phone:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('phone')}}

@if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>

<!--- Primary Contact -->
<div class="form-group @if ($errors->has('contact')) has-error @endif">
{{Form::label('contact','Primary Contact:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('contact')}}

@if ($errors->has('contact')) <p class="help-block">{{ $errors->first('contact') }}</p> @endif
</div>


<!--- Segment -->

@if($segments->count()>1)
<div class="form-group @if ($errors->has('segment')) has-error @endif">
{{Form::label('segment','Segment:',array('class'=>'control-label col-sm-2'))}}


{{Form::select('segment',$segments,isset($location->location->segment) ? $location->location->segment : $nullSegment)}}

@if ($errors->has('segment')) <p class="help-block">{{ $errors->first('segment') }}</p>
@endif

</div>
@endif


<!--- Business Type -->
<div class="form-group @if ($errors->has('businesstype')) has-error @endif">
{{Form::label('businesstype','Business Type:',array('class'=>'control-label col-sm-2'))}}

{{Form::select('businesstype',$businesstypes,isset($location->location->businesstype) ? $location->location->businesstype : $nullBusinesstype )}}
@if ($errors->has('businesstype')) <p class="help-block">{{ $errors->first('businesstype') }}</p> @endif
</div>


{{Form::hidden('company_id',$companyid)}}

<!-- Form Actions -->
	<div style="margin-top:20px">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('company.show',$location->location->company_id) }}">Cancel</a>

			

			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>