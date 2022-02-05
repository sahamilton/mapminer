<table>
    <thead>
        <tr>
            <th>Oracle vs Mapminer Data</th>
        </tr>
        <tr>
           <th></th>
        </tr>
        <tr>
            <th>Oracle ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>Mapminer Roles</th>
            <th>Oracle Roles</th>
            <th>Oracle Manager</th>
            <th>Oracle Manager Role</th>
            <th>Oracle Manager Email</th>

        </tr>
    </thead>
    <tbody>

        @foreach ($users as $user)
            <tr>
                <td>{{ $user->person_number }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->primary_email }}</td>
                <td>{{ $user->location_name }}</td>
                <td>
                    @if(isset($user->mapminerUser))
                        @foreach($user->mapminerUser->roles as $role)
                            <li>{{ $role->display_name }}</li>
                        @endforeach
                    @else
                        Not in Mapminer
                    @endif
                </td>
                <td>{{$user->job_profile}}</td>
                
                    @if($user->oracleManager)
                        <td>{{$user->oracleManager->fullName()}}</td>
                        <td>{{$user->oracleManager->job_profile}}</td>
                        <td>{{$user->oracleManager->primary_email}}</td>
                    @else
                        <td></td><td></td><td></td>
                    @endif
                
            </tr>
        @endforeach
        
    </tbody>
</table>