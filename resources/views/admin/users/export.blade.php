<table>
<thead> 
    <tr>
        <td>Registered Mapminer Users (with soft deleted)</td>
    </tr>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Employee ID</th>
        <th>EMail</th>
        <th>Roles</th>
        <th>ServiceLine</th>
        <th>Reports To</th>
        <th>Managers Employee ID</th>
        <th>Last Activity</th>
        <th>Deleted</th>
    </tr>
</thead>
<tbody>
@foreach ($users as $user)

    <tr>
        <td>{{ $user->person->firstname }}</td>
        <td>{{ $user->person->lastname }}</td>
        <td>{{$user->employee_id}}</td>
        <td>{{ $user->email }}</td>
        <td>
        @foreach ($user->roles as $role)
            {{ $role->display_name }}<br />
        @endforeach
        </td>
        <td>
            @foreach ($user->serviceline as $serviceline)
                {{ $serviceline->ServiceLine }}<br />
            @endforeach
        </td>
        <td>{{$user->person->reportsTo ? $user->person->reportsTo->fullName() : ''}}</td>
        <td>{{$user->person->reportsTo && $user->person->reportsTo->userdetails ? $user->person->reportsTo->userdetails->employee_id : ''}}</td>
        <td>{{$user->lastlogin ? $user->lastlogin->format('M j, Y h:i a') : ''}}</td>
        <td>{{$user->deleted_at ? $user->deleted_at->format('Y-m-d') : ''}}</td>

    </tr>
@endforeach
</tbody>
</table>
