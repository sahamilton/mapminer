<table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <tr>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('employee_id')" role="button" href="#">
                        Employee ID
                        @include('includes._sort-icon', ['field' => 'employee_id'])
                </a>
            </th>
            <th class="col-md-2">
                
                    First Name
                    
            </th>
            <th class="col-md-2">
                
                    Last Name
                    
            </th>

            <th class="col-md-2">
                <a wire:click.prevent="sortBy('users.email')" role="button" href="#">
                    Email
                    @include('includes._sort-icon', ['field' => 'users.email'])
                </a>
            </th>
            <th class="col-md-2">Roles</th>
            <th class="col-md-2">Service Lines</th>
            <th class="col-md-2">Manager</th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('lastlogin')" role="button" href="#">
                    LastLogin
                    @include('includes._sort-icon', ['field' => 'users.lastlogin'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('updated_at')" role="button" href="#">
                    LastUpdate
                    @include('includes._sort-icon', ['field' => 'users.updated_at'])
                </a>
            </th>
            
            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
    <tr>
        <td class="col-md-2">{{ $user->employee_id }}
            @if(! $user->oracleMatch)  
                <i class="fas fa-not-equal text-danger" title="Not in Mapminer"></i>
                @else
                <i class="fas fa-equals text-success" title="In Mapminer"></i>
                 @endif

        </td>
        <td class="col-md-2">
            <a href="{{route('users.show',$user->id)}}">
                {{$user->person->firstname}}
            </a>
        </td>
        <td class="col-md-2">
            <a href="{{route('users.show',$user->id)}}">
                {{$user->person->lastname}}
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
        <td>
            @if(isset($user->person->reportsTo->user_id))
                <a href="{{route('users.show',$user->person->reportsTo->user_id)}}">
                      {{$user->person->reportsTo->fullName()}}
                </a>
            
            @endif
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
                    @if($user->deleted_at)
                    
                        <a class="dropdown-item" 
                            data-href="{{route('users.permdestroy',$user->id)}}" 
                            data-toggle="modal" 
                            data-target="#confirm-delete" 
                            data-title = "{{$user->deletedperson->fullName()}}" href="#">
                            <i class="far fa-trash-alt text-danger" 
                            aria-hidden="true"> </i> 
                            Permanently Delete  {{$user->deletedperson->fullName()}}</a>

                    @else
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
                    @endif
                </ul>
            </div>
        </td>

        
    </tr>
@endforeach
            
        </tbody>

    </table>

