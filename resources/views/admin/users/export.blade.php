<table>
<thead> 
    <tr>
        <td>First Name</td>
        <td>Last Name</td>
        <td>User Name</td>
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
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>
        @foreach ($user->roles as $role)
            {{ $role->name }}<br />
        @endforeach
        </td>
        <td>
        @foreach ($user->serviceline as $serviceline)
            {{ $serviceline->ServiceLine }}<br />
        @endforeach
        </td>
        <td>
        @if(isset($user->lastlogin) &&  $user->lastlogin != '0000-00-00 00:00:00'  )
            {{$user->lastlogin->format('M j, Y h:i a')}}
        @endif
        </td>
    </tr>
@endforeach
</tbody>
</table>
