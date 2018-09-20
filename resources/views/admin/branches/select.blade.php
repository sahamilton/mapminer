@extend('admin.layouts.default')
@section('content')
<p>Select Roles to confirm branch assignments. Emails will be sent to all in the chosen roles who have not confirmed recently.</p>
<form action="{{route('admin.branchteam.email')}}" name="selectroles" method="post" >
@csrf
<div class="form-group{{ $errors->has('roles)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Roles</label>
    <div class="col-md-6">
        <select class="form-control" mutiple name='roles[]'>
        @foreach ($roles as $key=>$value))
           
				<option value="{{$key}}">{{$value}}</option>

        @endforeach

        </select>
        <span class="help-block{{ $errors->has('roles)') ? ' has-error' : '' }}">
            <strong>{{ $errors->has('manager') ? $errors->first('manager') : ''}}</strong>
            </span>
    </div>
</div>
<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				
				<button type="submit" class="btn btn-success">Email Choosen Roles</button>
			</div>
		</div>
</form>
@endsection