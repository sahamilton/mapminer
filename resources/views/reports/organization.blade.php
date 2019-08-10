<table>
    <thead>
        <tr><th colspan="10">Organization Report</th></tr>
        
        <tr>
            <th><b>Person</b></th>
            <th><b>Role</b></th>
            <th><b>City</b></th>
            <th><b>State</b></th>
            <th><b>Branches Serviced</b></th>
            <th><b>Reporting</b></th>
            
        </tr>

    </thead>
    <tbody>
        @foreach ($people as $person)
        <tr>
            <td>{{$person->fullName()}}</td>
            <td>
                @foreach ($person->userdetails->roles as $role)
                    {{$role->display_name}}<br />
                @endforeach
            </td>
            <td>{{$person->city}}</td>
            <td>{{$person->state}}</td>
            <td>
                @foreach ($person->branchesServiced as $branch)
                    <li>{{$branch->branchname}}</li>

                @endforeach
            <td>
                @foreach ($person->getAncestors() as $manager)
                    {{$manager->fullName()}} |
                @endforeach
            </td>

        </tr>
        @endforeach
    </tbody>
</table>