<div>   
    <p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
    <div class="row mb4" style="padding-bottom: 10px">
            
            <div class="col mb8">
                <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        
                <input wire:model="search" class="form-control" type="text" placeholder="Search companies...">
            </div></div>
        </div>
       
    <div class="row mb-4 ">
        @include('livewire.partials._perpage')
        <div class="col form-inline">
            <label for="status">Status:</label>
            <select wire:model="status" 
            class="form-control">
                <option value="All">All</options>
                <option value='1'>Complete</options>
                <option value=''>Planned</options>
                
            </select>
        </div>
        @include('livewire.partials._periodselector')
         <div class="col form-inline">
            <label for="activitytype">Type:</label>
            <select wire:model="activitytype" 
            class="form-control">
                <option value="All">All</option>
                @foreach ($activitytypes as $type)
                    <option value="{{$type->id}}">{{$type->activity}}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-4 ">

        </div>

    
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('activity_date')" role="button" href="#">
                    Activity Date
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                </a>
            </th>
            <th>Activity</th>
            <th>Status</th>
            <th>Type</th>
            <th>
                <a wire:click.prevent="sortBy('branch_id')" role="button" href="#">
                Branch
                @include('includes._sort-icon', ['field' => 'branch_id'])
                </a>
            </th>
            <th>
            <a wire:click.prevent="sortBy('created_at')" role="button" href="#">
                Created or Updated At
                @include('includes._sort-icon', ['field' => 'created_at'])
                </a>
            </th>
        </thead>
        <tbody>
        @foreach ($activities as $activity)
          
            <tr>
               <td><a href="{{route('address.show', $activity->address_id)}}">{{$activity->relatesToAddress->businessname}}</a></td> 
               <td>{{$activity->activity_date->format('Y-m-d')}}</td> 
               <td>{{$activity->note}}</td> 
               <td> 
                    {{$activity->completed ==1 ? 'Completed' : 'Planned'}}
                    @if($activity->completed !=1 && $activity->activity_date < now())
                        <i class="fas fa-exclamation-triangle text-danger" title="Overdue activity"></i>
                    @endif
               </td> 
               <td>{{$activity->type->activity}}</td>
               <td>{{$activity->branch_id}}</td>
               <td>{{max($activity->created_at, $activity->updated_at)->format('Y-m-d')}}</td>
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
