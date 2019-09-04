@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Confirm Delete Users</h2>
    <form name="massdelete"
        action="{{route('users.massdelete')}}"
        method="post"
        id="massdelete"
        >
    @csrf
    <table id="sorrtable" class="table table-striped table-bordered">
        <thead>
            <th>Employee</th>
            <th>Employee Id</th>
            <th>Roles</th>
            <th>Title</th>
            <th>Reports To</th>
            <th>Direct Reports</th>
            <th>Delete</th>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{$user->person->fullName()}}</td>
                <td>{{$user->employee_id}}</td>
                <td>
                    @foreach ($user->roles as $role)
                    <li>{{$role->display_name}}</li>
                    @endforeach
                </td>
                <td>{{$user->person->business_title}}</td>
                <td>
                    @if($user->person->reportsTo)
                        {{$user->person->reportsTo->fullName()}}
                    @endif
                </td>
                <td>
                    @foreach($user->person->directReports as $reports)
                    <li>{{$reports->fullName()}}</li>
                    @endforeach
                </td>
                <td>
                    <input type="checkbox" name="user_id[]" value="{{$user->id}}" 
                    @if($user->person->directReports->count() == 0)
                    checked
                    @else
                    disabled
                    @endif
                     />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <input type="submit"
    class="btn btn-danger"
    value="Delete Checked"
    />
</form>
</div>

@endsection