<table>
    <thead>
        <tr>
            <th>Oracle / Mapminer Data</th>
        </tr>
        <tr>
           <th>@if(isset($filter)) {{$filter}} @endif {{$title}}</th>
        </tr>
        <tr>
            <th>Employee ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>LastLogin</th>

        </tr>
    </thead>
    <tbody>

        @foreach ($users as $user)
            <tr>
                <td>{{ $user->employee_id }}</td>
                <td>{{$user->firstname}}</td>
                <td>{{$user->lastname}}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <li>{{ $role->display_name }}</li>
                    @endforeach
                    
                </td>
                
                <td>{{$user->lastlogin ? $user->lastlogin->format('M j, Y'): ''}}</td>
                
            </tr>
        @endforeach
        
    </tbody>
</table>