<<<<<<< HEAD
@extend('admin.layouts.default')
@section('content')
<p>Select Roles to confirm branch assignments. Emails will be sent to all in the chosen roles who have not confirmed recently.</p>
<form action="{{route('admin.branchteam.email')}}" name="selectroles" method="post" >
@csrf
<div class="form-group{{ $errors->has('roles)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Roles</label>
    <div class="col-md-6">
        <select class="form-control" mutiple name='roles[]'>
=======
@extends('admin.layouts.default')
@section('content')
<h2>Send Emails to Confirm Branch Associations</h2>

<p>Edit the body of the email message or leave default.</p>
<form action="{{route('branchteam.email')}}" name="selectroles" method="post" >
@csrf
<div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
         <label class="control-label">Message text</label>
          <div class="input-group input-group-lg ">
             <textarea required class=" summernote" 
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
>>>>>>> development
        @foreach ($roles as $key=>$value))
           
				<option value="{{$key}}">{{$value}}</option>

        @endforeach

        </select>
        <span class="help-block{{ $errors->has('roles)') ? ' has-error' : '' }}">
<<<<<<< HEAD
            <strong>{{ $errors->has('manager') ? $errors->first('manager') : ''}}</strong>
            </span>
    </div>
</div>
<div class="form-group">
=======
            <strong>{{ $errors->has('roles') ? $errors->first('roles') : ''}}</strong>
            </span>
    </div>
</div>
<div class="form-group row">
    <div class=" form-check-inline"">
      <input class="form-check-input" type="checkbox" checked id="test" name="test">
      <label class="form-check-label" for="test">
        Check if Test:</label>
      
      
    </div>
  </div>
<div class="form-group row">
>>>>>>> development
			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">Email Choosen Roles</button>
			</div>
		</div>
</form>
<<<<<<< HEAD
=======
<script>

$('.summernote').summernote({
      height: 300,                 // set editor height
    
      minHeight: null,             // set minimum height of editor
      maxHeight: null,             // set maximum height of editor
    
      focus: true,                 // set focus to editable area after initializing summernote
      toolbar: [
    //[groupname, [button list]]
     
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['link'],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['misc',['codeview']],
    
  ]
});
 

</script>
>>>>>>> development
@endsection