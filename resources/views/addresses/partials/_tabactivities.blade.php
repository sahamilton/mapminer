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
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Date</th>
    <th>Created by</th>
    <th>Activity</th>
    <th>Contact</th>
    <th>Notes</th>
    <th>Completed</th>
    @if($owned)
    <th>Actions</th>
    @endif
    </thead>
    <tbody>
         @foreach($location->activities as $activity)
  
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
                @if($owned)
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">

                        
                        
                        <a class="dropdown-item" 
                        title="Edit Activity"
                          href="{{route('activity.edit',$activity->id)}}">
                        <i class="far fa-edit text-info" aria-hidden="true"> </i>
                        Edit activity</a>
                        @if(! $activity->completed)
                        <a class="dropdown-item"
                        title="Complete Activity"
                          href="{{route('activity.complete',$activity->id)}}" 
                          >
                          <i class="fas fa-clipboard-check"></i>
                           Mark As Complete
                        </a>

                        @endif
                        
                        <a class="dropdown-item"
                        title="Delete Activity"
                          data-href="{{route('activity.destroy',$activity->id)}}" 
                          data-toggle="modal" 
                          data-target="#confirm-delete" 
                          data-title = "activity" 
                          href="#">
                          <i class="far fa-trash-alt text-danger" 
                            aria-hidden="true"> </i>
                           Delete Activity
                        </a>

                        </ul>
                    </div>
                </td>
                @endif
            </tr>
           @endforeach

    </tbody>
</table>
@include('addresses.partials._activities')
