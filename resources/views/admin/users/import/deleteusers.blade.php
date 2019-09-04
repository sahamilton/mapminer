@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Delete Users</h2>
    <form class="form" 
    name="deleteusers" 
    method="post" 
    action="{{route('users.bulkdelete')}}">
    @csrf
        <div class="form-group">
             <label class="col-md-4 control-label">
                Enter User Employee ID's to delete:
             </label>
            <div class="input-group input-group-lg">
           
                <textarea name="user_ids"></textarea>
            </div>
        </div>
        <input type="submit" class="btn btn-info" value="Delete Users" />
    </form>
</div>

@include('partials._scripts')
@endsection
