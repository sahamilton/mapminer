<div>
{{Form::label('firstname','First Name:')}}
<div>
{{Form::text('firstname')}}
{{ $errors->first('firstname') }}
</div></div>


<div>
{{Form::label('lastname','Last Name:')}}
<div>
{{Form::text('lastname')}}
{{ $errors->first('lastname') }}
</div></div>

<div>
{{Form::label('email','EMail:')}}
<div>
{{Form::text('email')}}
{{ $errors->first('email') }}
</div></div>

<div>
{{Form::label('mgrtype','Function')}}
<div>
{{Form::select('mgrtype',array('account'=>"National Account Manager",'branch'=>"Market Manager"))}}
{{ $errors->first('mgrtype') }}
</div></div>

<div>
{{Form::label('position','Position')}}
<div>
{{Form::select('position',$positions))}}
{{ $errors->first('position') }}
</div></div>

<div>
{{Form::label('reportsTo','Reports to:')}}
<div>
{{Form::select('reportsto',$salemanagers))}}
{{ $errors->first('reportsto') }}
</div></div>
	
	<!-- Form Actions -->
    
    <div style="margin-top:20px">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('person.index') }}">Cancel</a>

			<button type="reset" class="btn">Reset</button>

			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>

    
	