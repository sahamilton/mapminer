<div>
    <h2>Oracle HR Data compared to Mapminer</h2>
    <h4>Matched Email but Unmatched Employee #</h4>
    <p><a href="{{route('oracle.index')}}">Return to Oracle Data</a></p>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <div>
        <table class='table table-striped table-bordered table-condensed table-hover'>
            <thead>
                <th class="col-md-2">
                    <a wire:click.prevent="sortBy('last_name')" 
                        role="button" 
                        href="#">
                            Name
                            @include('includes._sort-icon', ['field' => 'last_name'])
                    </a>
                </th>
                
                <th>
                    <a wire:click.prevent="sortBy('email')" 
                        role="button" 
                        href="#">
                        Email
                    </a>
                </th>
                <th>
                    <a wire:click.prevent="sortBy('manager_name')" 
                        role="button" 
                        href="#">
                        Manager
                    </a>
                </th>
                <th>
                    <a wire:click.prevent="sortBy('person_number')" 
                        role="button" 
                        href="#">
                        Oracle Employee #
                    </a></th>
                <th>
                    <a wire:click.prevent="sortBy('employee_id')" 
                        role="button" 
                        href="#">
                        Mapminer Employee #
                    </a>
                </th>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="{{route('users.show', $user->id)}}">
                            {{$user->fullName()}}
                        </a>
                    </td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->manager_name}}</td>
                    <td>{{$user->person_number}}</td>

                    <td>
                        {{$user->employee_id}}
                        
                            <a 
                             wire:click="updateEmployeeNumber({{$user->id}},'{{$user->person_number}}')" 
                                title="Update Mapminer Employee #">
                                <i class="fas fa-user-edit text-warning"></i>
                               
                            </a>
                            
                      
                    </td>
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
</div>
