@if($owned)
     <div class="float-right">
        <a class="btn btn-info" 
            title="Add Activity"
            data-href="{{route('activity.store')}}" 
            data-toggle="modal" 
            data-target="#add_activity" 
            data-title = "Add activity to lead" 
            href="#">
            <i class="fas fa-pencil-alt"></i>
            Add Activity
            </a>
    </div>
@endif
<div class="col form-inline">
    @include('livewire.partials._perpage')
   
    @include('livewire.partials._search', ['placeholder'=>'Search activities'])
    <div  wire:loading>
        <div class="col spinner-border text-danger"></div>
    </div>
</div>
 <table  class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Date</th>
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
                <td>{{$activity->note}}</td>
                <td>{{$activity->completed ? 'Completed' : ''}}</td>
            </tr>
           @endforeach

    </tbody>
</table>

