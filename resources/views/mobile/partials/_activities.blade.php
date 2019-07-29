<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Company</th>
    <th>Planned Date</th>
    <th>Activity</th>
    <th>Actions</th>
    </thead>
    <tbody>
         @foreach($results as $activity)
  
            <tr>
                <td><a href="{{route('mobile.show',$activity->relatesToAddress->id)}}">{{$activity->relatesToAddress->businessname}}</a></td>
                <td>{{$activity->activity_date ? $activity->activity_date->format('M j, Y'):''}}</td>
                
                
                <td>@if($activity->type)
                    {{$activity->type->activity}}
                    @endif
                </td>
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
                        <i class="far fa-edit text-info"" aria-hidden="true"> </i>
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
            </tr>
           @endforeach

    </tbody>
</table>

