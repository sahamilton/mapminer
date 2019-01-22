<table>
    <thead>
        <tr>
            <td>pid</td>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Employee ID</td>
            <td>EMail</td>
            <td>Roles</td>
            <td>Service Lines</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($people as $person)
        <tr>
            <td>{{ $person->id }}</td>
            <td>
                @if(isset($person->firstname))
                    {{$person->firstname}}
                @endif
            </td>
            <td>
                @if(isset($person->lastname))
                    {{$person->lastname}}
                @endif
            </td>
            <td>{{ $person->userdetails->employee_id }}</td>
            <td>{{ $person->userdetails->email }}</td>
            <td>
                @foreach($person->userdetails->roles as $role)
                   {{ $role->displayName }}
                @endforeach
            </td>
            <td>
                @foreach($person->userdetails->serviceline as $serviceline)
                    {{$serviceline->ServiceLine }}
                @endforeach
            </td> 
        </tr>
        @endforeach
    </tbody>
</table>