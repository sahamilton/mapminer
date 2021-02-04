<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Person</th>
        <th>Role</th>
        <th>Logins</th>
        <th>First Login</th>
        <th>Last Login</th> 
    </thead>
    <tbody>
        @foreach ($people as $person)
        <tr>
            <td><a href="{{route('team.show',$person->id)}}">{{$person->fullName()}}</a></td>
            <td>
                @if(isset($person->userdetails))
                    @foreach ($person->userdetails->roles as $role)
                        <li>{{$role->display_name}}</li>
                    @endforeach
                @else
                    User deleted
                @endif
            </td>
            <td>{{$person->userdetails->usage->count()}}</td>
            @if($person->userdetails->usage->count()>1)
            <td>{{$person->userdetails->usage->min('created_at')->format('Y-m-d')}}</td>
            <td>{{$person->userdetails->usage->max('lastactivity')->format('Y-m-d')}}</td>
            @else
            <td></td><td>Never Logged In</td>
            @endif
        </tr>
        @endforeach

    </tbody>
</table>