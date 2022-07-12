<div>


    <h2> @if($roletype != 'all')
            {{$roles[$roletype]}}s
        @else 
            All People
        @endif
        
        
    </h2>
    
   
    
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search People'])    
        </div>
    </div>
    
    @include('notifications')

    <div class="row mb-4">
        <x-form-select wire:model="roletype"
                name='roletype'
                label="Roles:"
                :options='$roles'
                />
        @include('livewire.partials._periodselector', ['all'=>true])
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    
    </div>
    
    
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>User</th>
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
                        @foreach ($user->roles as $role)
                            {{$role->display_name}}
                        @endforeach
                    </td>
                    <td>{{$user->created_at->format('Y-m-d')}}</td>
                    <td>{{$user->lastlogin ? $user->lastlogin->format('Y-m-d') : ''}}</td>
                    <td>{{$user->usage_count}}</td>

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
