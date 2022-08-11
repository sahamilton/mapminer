<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>
            <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                Person
                @include('includes._sort-icon', ['field' => 'lastname'])
            </a>
        </th>
        <th>Role</th>
        <th>Logins</th>
        <th>Mapminer User Since</th>
        <th>
        <a wire:click.prevent="sortBy('lastlogin')" role="button" href="#">
                Last Login
                @include('includes._sort-icon', ['field' => 'lastlogin'])
            </a>
        </th> 
    </thead>
    <tbody>
        @foreach ($team as $person)
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
            <td>{{$person->userdetails->logins_count}}</td>
            
            <td>{{$person->created_at->format('Y-m-d')}}</td>
            @if($person->userdetails->lastlogin)
                <td>{{$person->userdetails->lastlogin->format('Y-m-d')}}</td>
            @else
               <td>Never Logged In</td>
            @endif
        </tr>
        @endforeach

    </tbody>
</table>