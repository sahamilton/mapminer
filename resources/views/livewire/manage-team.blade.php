<div>
    <h2>{{$user->fullName()}}'s Team</h2>
    <h4>Based on Oracle HRMS</h4>

    <p>
        <a href=""
            wire:click.prevent = changeUser({{$user->person->reportsTo->user_id}})
             title= "See {{$user->person->reportsTo->full_name}}'s team">
             Reports to {{$user->person->reportsTo->full_name}}
        </a>
        
    </p>
    <p><a href="{{route('user.show', $user->id)}}">Return to Profile</a></p>
    <div class="row mb4" style="padding-bottom: 10px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Team'])
        </div>
    
    </div>

    
    <table  style="margin-top:20px" class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>

                <a wire:click.prevent="sortBy('first_name')" role="button" href="#">
                    First Name
                    @include('includes._sort-icon', ['field' => 'first_name'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('last_name')" role="button" href="#">
                    Last Name
                    @include('includes._sort-icon', ['field' => 'last_name'])
                </a>
            </th>
            <th># Reports</th>
            <th>Mapminer Role</th>
            <th>Mapminer Branches</th>
            <th>
                <a wire:click.prevent="sortBy('job_profile')" role="button" href="#">
                    Oracle Job
                    @include('includes._sort-icon', ['field' => 'job_profile'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('location_name')" role="button" href="#">
                    Location
                    @include('includes._sort-icon', ['field' => 'location_name'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('lastlogin')" role="button" href="#">
                    Last Login
                    @include('includes._sort-icon', ['field' => 'lastlogin'])
                </a>
            </th>
        </thead>
        <tbody>
            @foreach($team as $member)
           
            <tr>
                <td>
                    @if($member->mapminerUser)
                        <i 
                            class="fa-solid fa-circle-check  text-success"
                            title= "{{$member->fullName()}} is registered in Mapminer">
                        </i>
                        <a href=""
                            wire:click.prevent = changeUser({{$member->mapminerUser->id}})
                             title= "See {{$member->fullName()}}'s team">
                            {{$member->first_name}}
                        </a>
                    @else
                        
                        <span title= "{{$member->fullName()}} is not registered in Mapminer">

                        {{$member->first_name}} </span>
                        <a href="{{route('oracle.useradd', $member->id)}}"
                          title="Add {{$member->fullName()}} to Mapminer">
                          <i class="fa-solid fa-circle-plus text-danger"></i> Add </a>
                        
                        
                    @endif
                </td>
                <td>
                    @if($member->mapminerUser)
                    
                    <a href=""
                        wire:click.prevent = changeUser({{$member->mapminerUser->id}})
                         title= "See {{$member->fullName()}}'s team">
                        {{$member->last_name}}
                    </a>
                    @else
                       <span title= "{{$member->fullName()}} is not registered in Mapminer">{{$member->last_name}}</span>
                        
                        
                    @endif
                </td>
                <td>{{$member->teamMembers->count()}}</td>
                <td>
                    {{$member->mapminerUser ? $member->mapminerUser->roles->first()->display_name : ''}}
                </td>
                <td>
                    @if($member->mapminerUser) 
                        @foreach ($member->mapminerUser->person->branchesServiced as $branch)
                            <li>{{$branch->branchname}}</li>
                        @endforeach
                    @endif
                </td>
                
                <td>{{$member->job_profile}}</td>
                <td>{{$member->location_name}}</td>
                <td>{{$member->mapminerUser ? $member->mapminerUser->lastlogin : ''}}</td>
               
            </tr>
            @endforeach
        </tbody>

    </table>
    <div class="row">
            <div class="col">
                {{ $team->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $team->firstItem() }} to {{ $team->lastItem() }} out of {{ $team->total() }} results
            </div>
        </div>
    </div>
</div>
