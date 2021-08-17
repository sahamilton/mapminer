<div>
    <h2>
        @if($role_id == 'All') 
            All Managers
        @else 
            {{$roles[$role_id]}}s
        @endif
        @if($branchcount != 'All' ||  $directReports != 'All' || $setPeriod != 'All')
        <span class="text text-danger"><em> filtered</em></span>
        @endif
    </h2>
    <h4>
        @if($setPeriod === 'All')

        @else
        With Logins between {{$period['from']->format('M jS, Y')}} and {{$period['to']->format('M jS, Y')}}
        @endif
    </h4>
    <div class="row" style="margin-top:5px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <strong> Select Roles: </strong>
            <select wire:model="role_id" class="form-control">
                <option value="All">All</option>
                @foreach ($roles as $key=>$value)
                <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            @include('livewire.partials._search', ['placeholder'=>'Search Managers'])
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top:10px">
        <div class="col form-inline">
            <strong>Filter:</strong>
            <i class="fas fa-filter text-danger"></i>
            
             Branches: &nbsp;
            <select wire:model="branchcount" class="form-control">
                <option value="All">All</option>
                <option value="yes">With Branches</option>
                <option value="no">Without Branches</option>
                
            </select>
             Direct Reports: &nbsp;
            <select wire:model="directReports" class="form-control">
                <option value="All">All</option>
                <option value="yes">With Direct Reports</option>
                <option value="no">Without Direct Reports</option>
                
            </select>
            @include('livewire.partials._periodselector', ['title'=>'Logins', 'all'=>true])
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
                <th>
                    <a wire:click.prevent="sortBy('lastlogin')" role="button" href="#">
                        Last Login
                        @include('includes._sort-icon', ['field' => 'lastlogin'])
                    </a>
             </th>
            </thead>
            <tbody>
                @foreach ($managers as $manager)                 
                <tr>
                    <td>
                         <a href="{{route('person.details', $manager->id)}}">
                            {{$manager->fullName()}}
                        </a>
                    </td>
                    <td>
                        @foreach ($manager->userdetails->roles as $role)
                            {{! $loop->first ? "," :''}}
                            {{$role->display_name}}
                        @endforeach
                    </td>
                    <td>
                        @if(! is_null($manager->reports_to))
                            <a href="{{route('person.details', $manager->reports_to)}}">
                                {{$manager->reportsTo->fullName()}}
                            </a>
                        @else
                             {{$manager->reportsTo->fullName()}}
                        @endif 
                    </td>
                    <td>{{$manager->direct_reports_count}}</td>
                    <td>{{$manager->branchesserviced_count}}</td>
                    <td>{{$manager->userdetails->lastlogin ? $manager->userdetails->lastlogin->format('Y-m-d') : ''}}</td>
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
