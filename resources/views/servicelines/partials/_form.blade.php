
<?php $colors= ['blue','yellow','green','red'];?>

<div class="form-group row @if ($errors->has('Serviceline')) has-error @endif">
	<div class= "col-md-2" > 
	{{Form::label('ServiceLine','Service Line:')}}
	</div>
	<div class= "col-md-6" > 
	{{Form::text('ServiceLine',isset($serviceline->ServiceLine) ? $serviceline->ServiceLine :'',array('class'=>'form-control'))}}
	 @if ($errors->has('ServiceLine')) <p class="help-block">{{ $errors->first('ServiceLine') }}</p> @endif
	</div>
</div>

<div class="form-group row @if ($errors->has('Serviceline')) has-error @endif">
	<div class= "col-md-2" > 
	{{Form::label('color','Pin Color:')}}
	</div>
	<div class= "col-md-6" > 
	<select class='form-control' name='color' >
	@foreach ($colors as $color)

	<option value="{{$color}}" @if (isset($serviceline) && $serviceline->color == $color)  selected  @endif >{{$color}}</option>
	@endforeach
	</select>
	 @if ($errors->has('color')) <p class="help-block">{{ $errors->first('color') }}</p> @endif
	</div>
</div>



<!-- Form Actions -->
	<div style="margin-top:20px" class= "row col-md-4" >
		<div class="controls">
			<a class="btn btn-link" href="{{ route('admin.serviceline.index') }}">Cancel</a>

			

			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>