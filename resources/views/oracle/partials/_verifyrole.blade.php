
<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        
        <th class="col-md-2">
            <a wire:click.prevent="sortBy('person')" 
                role="button" 
                href="#">
                    Name
                    @include('includes._sort-icon', ['field' => 'person'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('oraclemanager')" 
                role="button" 
                href="#">
                Oracle Manager
                @include('includes._sort-icon', ['field' => 'oraclemanager'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('mapminermanager')" 
                role="button" 
                href="#">
                Mapminer Manager
                @include('includes._sort-icon', ['field' => 'mapminermanager'])
            </a>
        </th>
        
        <th>
            <a wire:click.prevent="sortBy('MMRole')" 
                role="button" 
                href="#">
                Mapminer Role
                @include('includes._sort-icon', ['field' => 'MMRole'])
            </a></th>
        <th>
            <a wire:click.prevent="sortBy('profile')" 
                role="button" 
                href="#">
                Oracle Job
                @include('includes._sort-icon', ['field' => 'profile'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('OracleRole')" 
                role="button" 
                href="#">
                MM Role for Oracle Job
                @include('includes._sort-icon', ['field' => 'OracleRole'])
            </a>
        </th>
        <th>
            <a wire:click.prevent="sortBy('lastlogin')" 
                role="button" 
                href="#">
                Last Login
                @include('includes._sort-icon', ['field' => 'lastlogin'])
            </a>
        </th>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>
                <a href="{{route('users.show', $user->userId)}}">
                    {{$user->person}}
                </a>
            </td>
            <td {{$user->oraclemanager != $user->mapminermanager ? "class=bg-warning":''}}
                title="Manager different than in Mapminer">
                {{$user->oraclemanager}}
                
            </td>
            <td {{$user->oraclemanager != $user->mapminermanager ? "class=bg-warning":''}}
                title="Manager different than in Oracle">
                {{$user->mapminermanager}}
            </td>
            <td>{{$user->MMRole}}</td>
            <td>{{$user->profile}}</td>

            <td>
                {{$user->OracleRole}} /
                {{$user->OracleRoleID}}
                   <a 
                     wire:click="updateEmployeeRole({{$user->userId}},'{{ $user->OracleRoleID}}')" 
                        title="Update {{$user->person}} from {{$user->MMRole}} to {{$user->OracleRole}}">
                        <i class="fas fa-user-edit text-warning"></i>
                       
                    </a>
                    
              
            </td>
            <td>{{$user->lastlogin}}</td>
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
