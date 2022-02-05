<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <tr>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('person_number')" role="button" href="#">
                        Employee ID
                        @include('includes._sort-icon', ['field' => 'person_number'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('first_name')" role="button" href="#">
                    First Name
                    @include('includes._sort-icon', ['field' => 'first_name'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('last_name')" role="button" href="#">
                    Last Name
                    @include('includes._sort-icon', ['field' => 'last_name'])
                </a>
            </th>

            <th class="col-md-2">
                <a wire:click.prevent="sortBy('primary_email')" role="button" href="#">
                    Email
                    @include('includes._sort-icon', ['field' => 'primary_email'])
                </a>
            </th>
            <th class="col-md-2">Oracle Role</th>
            <th class="col-md-2">Mapminer Role</th>
            <th class="col-md-2">Location</th>
            <th class="col-md-2">Manager</th>
        
        
        </tr>
    </thead>
    <tbody>

     @foreach ($users as $user)
   
        <tr> 
            <td class="col-md-2">
                <a href="{{route('oracle.show', $user->id)}}">
                    {{ $user->person_number }}
                </a>
                @if(! $user->mapminerUser)  
                <i class="fas fa-not-equal text-danger" title="Not in Mapminer"></i>
                @else
                <i class="fas fa-equals text-success" title="In Mapminer"></i>
                 @endif
            </td>
            <td class="col-md-2">
                <a href="{{route('oracle.show', $user->id)}}">
                    {{$user->first_name}}
                </a>
            </td>
            <td class="col-md-2">
                <a href="{{route('oracle.show', $user->id)}}">
                    {{$user->last_name}}
                </a>

                @if(! $user->mapminerUser && isset($user->oracleManager->mapminerUser))  
                        <a href=""
                            wire:click.prevent="addUser({{$user->id}})"
                            title="Add {{$user->fullName()}} to Mapminer">
                            <i class="fas fa-user-plus text-success"></i>
                        </a>
                @endif
            </td>
            <td class="col-md-2">{{$user->primary_email }}</td>
            <td class="col-md-2">{{$user->job_profile}}</td>
            <td class="col-md-2">
                 @if($user->mapminerUser)
                    @foreach ($user->mapminerUser->roles as $role)
                        {{$role->display_name}}
                    @endforeach
                 @endif

            </td>
            <td class="col-md-2">{{$user->location_name}}</td>
            
            <td>
                @if(isset($user->oracleManager->mapminerUser))
                <a class="txt-success" href="{{route('users.show', $user->oracleManager->mapminerUser->id)}}" title="{{$user->manager_name}} is in Mapminer">
                    {{$user->manager_name}}
                </a>
                    
                @else
                    <span class="txt-danger"
                    title="{{$user->manager_name}} is NOT in Mapminer">{{$user->manager_name}}</span>
                @endif
                    @if(isset($user->oracleManager->mapminerUser->person) 
                    && isset($user->mapminerUser->person)
                    && $user->oracleManager->mapminerUser->person->id 
                    != $user->mapminerUser->person->reports_to)
                        <em class="text-danger">{{$user->oracleManager->mapminerUser->person->postName()}}</em>
                    @endif
            </td>
          
               
        </tr>
    @endforeach
        
    </tbody>

</table>
