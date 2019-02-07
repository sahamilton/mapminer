<table>
<thead> 
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
      
        <th>EMail</th>
        <th>Roles</th>
        <th>ServiceLine</th>
        <th>Last Activity</th>
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
