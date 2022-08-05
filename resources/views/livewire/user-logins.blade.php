<div>

  
    <h2> @if($roletype != 'all')
            {{$roles[$roletype]}}s
        @else 
            All People 
        @endif
    </h2>
    <h4>
        @if($setPeriod==='never')
            who Have never logged in.
        @else

        who {{$have}} logged in {{$have ==='have' ? 'between'. $period['from']->format('Y-m-d') . ' to '.$period['to']->format('Y-m-d')  : ' since '. $period['from']->format('Y-m-d')}}

        @endif
        
    </h4>
    
   
    
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search People'])    
        </div>
    </div>
    
    

    <div class="row mb-4">
        <div class="col form-inline">
            <x-form-select wire:model="roletype"
                    name='roletype'
                    label="Roles:"
                    :options='$roles'
                    />
            @include('livewire.partials._periodselector') 
            <x-form-select wire:model="have"
                    name='have'
                    label="Logged In:"
                    :options='$havehavent'
                    /> 
            <button class="btn btn-info" wire:click='emailManagers()'>Email managers</button>  
        </div>
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    
    </div>
    
    
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>User</th>
            <th>Reports To</th>
            <th>Roles</th>
            <th>
                <a wire:click.prevent="sortBy('created_at')" 
                    role="button" href="#" 
                >
                    Created
                    @include('includes._sort-icon', ['field' => 'created_at'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('lastlogin')" 
                    role="button" href="#" 
                >
                    Last login
                    @include('includes._sort-icon', ['field' => 'lastlogin'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('usage_count')" 
                    role="button" href="#" 
                >
                    Total Logins
                    @include('includes._sort-icon', ['field' => 'usage_count'])
                </a>

            </th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($users as $user)

                <tr>
                    <td>
                        <a href="{{route('user.show', $user->id)}}">
                            {{$user->person->fullName()}}
                        </a>
                    </td>
                    <td>
                        @if($user->person->reportsTo)

                            {{$user->person->reportsTo->completeName}}
                        @endif
                    </td>
                    <td>
                        @foreach ($user->roles as $role)
                            {{$role->display_name}}
                        @endforeach
                    </td>
                    <td>{{$user->created_at->format('Y-m-d')}}</td>
                    <td>{{$user->lastlogin ? $user->lastlogin->format('Y-m-d') : ''}}</td>
                    <td>{{$user->usage_count}}</td>
                    <td>{{$user->lastlogin && $user->lastlogin->diffInDays($user->created_at) >0 ? number_format($user->usage_count / $user->lastlogin->diffInDays($user->created_at),1) :''}}</td>

                </tr>
            @endforeach


        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $users->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
        </div>
    </div>
</div>
