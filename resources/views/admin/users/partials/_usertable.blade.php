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
        <td class="col-md-2">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">

                    <a class="dropdown-item"
                        href="{{route('users.edit',$user->id)}}">
                        <i class="far fa-edit text-info" 
                            aria-hidden="true"> 
                        </i>Edit {{$user->person ? $user->person->fullName(): 'this person'}}
                    </a>

                    <a class="dropdown-item" 
                        data-href="{{route('users.destroy',$user->id)}}" 
                        data-toggle="modal" 
                        data-target="#confirm-delete" 
                        data-title = "{{$user->person ? $user->person->fullName(): 'this person'}}" href="#">
                        <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
                        Delete  {{$user->person ? $user->person->fullName(): 'this person'}}
                    </a>

                </ul>
            </div>
        </td>

        
    </tr>
@endforeach
