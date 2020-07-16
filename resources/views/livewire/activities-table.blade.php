<div>
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
         <div class="col form-inline">
            <label for="activitytype">Type:</label>
            <select wire:model="activitytype" 
            class="form-control">
                <option value="All">All</options>
                @foreach ($activitytypes as $type)
                    <option {{$activitytype== $type->id ? 'selected' :''}} value="{{$type->id}}">{{$type->activity}}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-4 ">

        </div>

        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search companies...">
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
        </thead>
        <tbody>
        @foreach ($activities as $activity)
          
            <tr>
               <td><a href="{{route('address.show', $activity->address_id)}}">{{$activity->businessname}}</a></td> 
               <td>{{$activity->activity_date->format('Y-m-d')}}</td> 
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
