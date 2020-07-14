 @foreach ($users as $user)
    <tr>
        <td class="col-md-2">{{ $user->employee_id }}</td>
        <td class="col-md-2">
            <a href="{{route('users.show',$user->id)}}">
                {{$user->firstname}}
            </a>
        </td>
        <td class="col-md-2">
            <a href="{{route('users.show',$user->id)}}">
                {{$user->lastname}}
            </a>
        </td>
        <td class="col-md-2">{{ $user->email }}</td>
        <td class="col-md-2">
            <ul>
                @foreach($user->roles as $role)
                <li>
                    <a title="Show all {{$role->display_name}} users" 
                        href="{{route('roles.show',$role->id)}}">
                        {{ $role->display_name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </td>
        <td class="col-md-2">
            <ul>
                @foreach($user->serviceline as $serviceline)
                    <li>
                        <a href="{{route('serviceline.show',$serviceline->id)}}"> {{$serviceline->ServiceLine }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </td>
        <td>{{$user->lastlogin ? $user->lastlogin->format('M j, Y h:i a'): ''}}</td>
        <td>@if($user->updated_at) {{$user->updated_at->format('M j, Y h:i a')}} @endif</td>

        
    </tr>
@endforeach
