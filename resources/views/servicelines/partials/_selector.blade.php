<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
		<label class='col-md-2 control-label'>ServiceLines</label>
		<div class="col-md-6">

		<div class='input-group input-group-lg'>
			<select multiple name="serviceline[]" >
				@foreach($servicelines as $key=>$serviceline)
					<option value="{{$key}}">{{$serviceline}}</option>
				@endforeach
			</select>
			<span class="help-block{{ $errors->has('serviceline') ? ' has-error' : '' }}">
				<strong>{{$errors->has('serviceline') ? $errors->first('serviceline')  : ''}}</strong>
			</span>
			</div>
		</div>
	</div>