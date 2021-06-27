<div>
    <div class="row" style="margin-top:5px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            
            @include('livewire.partials._search', ['placeholder'=>'Search Branches'])
            <div wire:loading>
                <div class="spinner-border"></div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:5px">
        <div class="col form-inline">
            Roles: 
            <select multiple wire:model="role_ids" class="form-control">
                <option value="All">All</option>
                @foreach ($roles as $key=>$value)
                <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <table 
            class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>
                    <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                        Manager
                        @include('includes._sort-icon', ['field' => 'lastname'])
                    </a>
                    
                </th>
                <th>
                    Role
                </th>
                <th>Reports To</th>
                <th>Direct Reports</th>
                <th>Branches Serviced</th>
            </thead>
            <tbody>
                @foreach ($managers as $manager)
                <tr>
                    <td>{{$manager->fullName()}}</td>
                    <td>
                        @foreach ($manager->userdetails->roles as $role)
                            {{! $loop->first ? "," :''}}
                            {{$role->display_name}}
                        @endforeach
                    </td>
                    <td>{{$manager->reportsTo->fullName()}}</td>
                    <td>{{$manager->direct_reports_count}}</td>
                    <td>{{$manager->branchesserviced_count}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
            <div class="col">
                {{ $managers->links() }}
            </div>
            <div class="col text-right text-muted">
                Showing {{ $managers->firstItem() }} to {{ $managers->lastItem() }} out of {{ $managers->total() }} results
            </div>
        </div>
    </div>


</div>
