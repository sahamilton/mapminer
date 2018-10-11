@extends('admin.layouts.default')

@if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif
{{-- Content --}}
@section('content')
	<div class="page-header">

		<h3>Import Users</h3>

	</div>

<h2>Steps to import users</h2>
<ol>
<li>First create your csv file of projects from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to:
            <ol>
            @foreach ($requiredFields as $field)
                <li style="color:red">{{$field}}</li>
            @endforeach
        </ol>
 </li>
<li>Save the CSV file locally on your computer.</li>
<li>Select the file and import</li>
<li>Notify the new users that they should use the 'Forgot Password' link to set their password.  They will recieve an email with a link that includes a unique token.</li>
</ol>

{{ Form::open(array('route'=>'admin.users.bulkimport', 'files' => true)) }}


<div>


<div>
{{Form::file('upload')}}
{{ $errors->first('upload') }}
</div></div>
<div>
<!-- Service Lines -->
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
<div class="row">
<input class="btn btn-xs btn-success" type="submit" name="submit" value="Import Users" />
				<!-- ./ servicelines -->
</div>

</div>
</form>

    
@include('partials/_scripts')
@endsection
