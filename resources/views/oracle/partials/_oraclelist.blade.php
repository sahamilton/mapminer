<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <tr>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('id')" role="button" href="#">
                        Employee ID
                        @include('includes._sort-icon', ['field' => 'person_number'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('firstname')" role="button" href="#">
                    First Name
                    @include('includes._sort-icon', ['field' => 'first_name'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
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
            <th class="col-md-2">Role</th>
            <th class="col-md-2">Location</th>
            <th class="col-md-2">Manager</th>
        
        
        </tr>
    </thead>
    <tbody>

     @foreach ($users as $user)
   
        <tr @if($user->mapminerUser) class="bg-success" @else class="bg-warning" @endif>
            <td class="col-md-2">
                <a href="{{route('oracle.show', $user->person_number)}}">
                    {{ $user->person_number }}
                </a>
            </td>
            <td class="col-md-2">
                <a href="{{route('oracle.show', $user->person_number)}}">
                    {{$user->first_name}}
                </a>
            </td>
            <td class="col-md-2">
                <a href="{{route('oracle.show', $user->person_number)}}">
                    {{$user->last_name}}
                </a>
            </td>
            <td class="col-md-2">{{$user->primary_email }}</td>
            <td class="col-md-2">{{$user->job_profile}}</td>
            <td class="col-md-2">{{$user->location_name}}</td>
            
            <td>{{$user->manager_name}}</td>
          
               
        </tr>
    @endforeach
        
    </tbody>

</table>
