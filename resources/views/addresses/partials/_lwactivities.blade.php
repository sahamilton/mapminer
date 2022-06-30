@if($owned)
     <div class="float-right mb-4">
        <button class="btn btn-info" href="#" wire:click.prevent="addActivity({{ $address->id }})">
            Record Activity
        </button>
            
    </div>
@endif
<div class="col form-inline mx-4">
    @include('livewire.partials._perpage')
    @include('livewire.partials._search', ['placeholder'=>'Search activities'])

    
    
       <div class="col form-inline">
            <label for="activitytype">Type:</label>
            <select wire:model="activitytype" 
            class="form-control">
                <option value="All">All</option>
                @foreach ($activityTypes as $key=>$type)
                    <option value="{{$key}}">{{$type}}</option>
                @endforeach
            </select>
        </div>
    

    
</div>
 <table  class='mt-4 table table-striped table-bordered table-condensed table-hover'>
    <thead>

        <th>
            <a wire:click.prevent="sortBy('activity_date')" 
                    role="button" 
                    href="#" 
                   >
                        Date
                @include('includes._sort-icon', ['field' => 'activity_date'])
            </a>

        </th>
        <th>Created by</th>
        <th>Activity</th>
        <th>Contact</th>
        <th>Notes</th>
        <th>Completed</th>
    </thead>
    <tbody>
         @foreach($viewdata as $activity)
  
            <tr>
                <td>{{$activity->activity_date ? $activity->activity_date->format('Y-m-d'):''}}</td>
                <td>
                    @if($activity->user)
                        {{$activity->user->person->fullName()}}
                    @else
                    No Longer with Company
                    @endif
                </td>
                <td>@if($activity->type)
                    {{$activity->type->activity}}
                    @endif
                </td>
                <td>@foreach($activity->relatedContact as $contact)
                    <li>{{$contact->complete_name}}</li>
                    @endforeach
                    
                </td>
                <td>
                    @if($search !='')
                        {!! $activity->highlightWords($search) !!}
                    @else

                        {{$activity->note}}
                    @endif
                </td>
                <td>{{$activity->completed ? 'Completed' : ''}}</td>
            </tr>
           @endforeach

    </tbody>
</table>
    <div class="row">
        <div class="col">
            {{ $viewdata->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $viewdata->firstItem() }} to {{ $viewdata->lastItem() }} out of {{ $viewdata->total() }} results
        </div>
    </div>
    @include('activities.partials._modal')
</div>


