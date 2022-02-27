<div>
    <h2>{{$branch->branchname}}</h2>
    <h4>{{$statuses[$status]}} Activities</h4>
    <p class="bg-warning">For the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>

    <p><a href="{{route('branchdashboard.show', $branch->id)}}">
    <i class="fas fa-tachometer-alt"></i>
     Return To Branch {{$branch->id}} Dashboard</a></p>
   
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._branchselector')
            @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
       <label><i class="fas fa-filter text-danger"></i>&nbsp;&nbsp;Filter&nbsp;&nbsp;</label>
        @include('livewire.partials._periodselector')
        <div class="col form-inline">
            <label for="status">Status:</label>
            <select wire:model="status" 
            class="form-control">
                @foreach ($statuses as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
                
            </select>
        </div>
        
        <div class="col form-inline">
            <label for="selectuser">Team:</label>
            <select wire:model="selectuser" 
            class="form-control">
                <option value="All">All</option>
                
                @foreach ($team as $key=>$person)
                    <option value="{{$key}}">{{$person}}</option>
                @endforeach
            </select>

            
        </div>
       
        <div class="col form-inline">
            <label for="activitytype">Type:</label>
            <select wire:model="activitytype" 
            class="form-control">
                <option value="All">All</option>
                @foreach ($activitytypes as $key=>$type)
                    <option value="{{$key}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
        
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Company</th>
            <th>
                <a wire:click.prevent="sortBy('activity_date')" 
                role="button" href="#">
                    Activity Date
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                </a>
            </th>
            <th>Created / Updated</th>
            <th>Activity</th>
            <th>Status</th>
            <th>Type</th>
            <th>Created By</th>
        </thead>
        <tbody>
        @foreach ($activities as $activity)
          
            <tr>
               <td><a href="{{route('address.show', $activity->address_id)}}">{{$activity->relatesToAddress->businessname}}</a></td> 
               <td>{{$activity->activity_date->format('Y-m-d')}}</td> 
               <td>{{max($activity->created_at, $activity->updated_at)->format('Y-m-d')}}</td> 
               <td>{{$activity->note}}</td> 
               <td> 
                    {{$activity->completed ==1 ? 'Completed' : 'Planned'}}
                    @if($activity->completed !=1 && $activity->activity_date < now())
                        <i class="fas fa-exclamation-triangle text-danger" title="Overdue activity"></i>
                    @endif
               </td> 
               <td>{{$activity->type->activity}}</td>
               <td>
                   
                    {{$activity->user->person->fullName()}}
                   
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="row">
            <div class="col">
                {{ $activities->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} out of {{ $activities->total() }} results
            </div>
        </div>
    </div>
</div>
