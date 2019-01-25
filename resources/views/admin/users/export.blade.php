<table>
<thead> 
    <tr>
        <td>First Name</td>
        <td>Last Name</td>
      
        <td>EMail</td>
        <td>Roles</td>
        <td>ServiceLine</td>
        <td>Last Activity</td>
    </tr>
</thead>
<tbody>
@foreach ($users as $user)
    <tr>
        <td>{{ $user->person->firstname }}</td>
        <td>{{ $user->person->lastname }}</td>
      
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

        <td>{{ $user->lastlogin ? $user->lastlogin->format('M j, Y h:i a') : ''}}</td>

    </tr>
@endforeach
</tbody>
</table>
