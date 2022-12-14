@extends('admin.layouts.default')
@section('content')
<h2>Send Emails to Confirm Branch Associations</h2>
<p><a href="{{route('campaigns.index')}}">See prior email campaigns</a></p>
<p>Edit the body of the email message or leave default.</p>
<form action="{{route('branchteam.email')}}" name="selectroles" method="post" >
@csrf
<div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
         <label class="control-label">Message text</label>
          <div >
             <textarea required 
             class="summernote" 
             name='message' 
             title="message">{!!old('message', isset($message) ? $message : '') !!}</textarea>
                 <span class="help-block">
                 <strong>{{$errors->has('message') ? $errors->first('message')  : ''}}</strong>
                 </span>
         </div>
     </div> 
<p>Select Roles to confirm branch assignments. Emails will be sent to all in the chosen roles who have not confirmed recently.</p>


<div class="form-group row {{ $errors->has('roles)') ? ' has-error' : '' }}">
    <label class="control-label">Roles <em>(select multiple)</em></label>
    <div class="input-group input-group-lg ">
        <select class="form-control" required multiple name='roles[]'>

        @foreach ($roles as $key=>$value))
           
				<option value="{{$key}}">{{$value}}</option>

        @endforeach

        </select>
        <span class="help-block{{ $errors->has('roles)') ? ' has-error' : '' }}">
            <strong>{{ $errors->has('roles') ? $errors->first('roles') : ''}}</strong>
            </span>
    </div>
</div>
@include('servicelines.partials._selector')
<div class="form-group row">
    <div class=" form-check-inline"">
      <input class="form-check-input" type="checkbox" checked id="test" name="test">
      <label class="form-check-label" for="test">
        Check if Test:</label>
      
      
    </div>
  </div>
<div class="form-group row">

			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">Email Choosen Roles</button>
			</div>
		</div>
</form>
@include('emails.partials._scripts')
@endsection