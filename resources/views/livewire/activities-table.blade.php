<div>
    <h2>{{$branch->branchname}}</h2>
    <h4>Activities</h4>
    <p>for the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>
    <div class="row mb4" style="padding-bottom: 10px">
        @include('livewire.partials._branchselector')
        @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
    </div>

    <div class="row mb-4 ">
        @include('livewire.partials._perpage')
        <div wire:loading>
            <div class="spinner-border"></div>
        </div>
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
                @foreach ($activitytypes as $key=>$type)
                    <option value="{{$key}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-4 ">

        </div>
        <div wire:loading>
            Processing Payment...
        </div>
    
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                
                    Company
                   
            </th>
            <th>
                <a wire:click.prevent="sortBy('activity_date')" 
                role="button" href="#" 
                wire:loading.class="bg-danger">
                    Activity Date
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                </a>
            </th>
            <th>Created / Updated</th>
            <th>Activity</th>
            <th>Status</th>
            <th>Type</th>
        </thead>
        <tbody>
        @foreach ($activities as $activity)
          
            <tr>
               <td><a href="{{route('address.show', $activity->address_id)}}">{{$activity->relatesToAddress->businessname}}</a></td> 
               <td>{{$activity->activity_date->format('Y-m-d')}}</td> 
               <td>{{max($activity->created_at,$activity->updated_at)->format('Y-m-d')}}</td> 
               <td>{{$activity->note}}</td> 
               <td> 
                    {{$activity->completed ==1 ? 'Completed' : 'Planned'}}
                    @if($activity->completed !=1 && $activity->activity_date < now())
                        <i class="fas fa-exclamation-triangle text-danger" title="Overdue activity"></i>
                    @endif
               </td> 
               <td>{{$activity->type->activity}}</td>
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
