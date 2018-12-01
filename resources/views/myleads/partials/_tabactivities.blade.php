 <div class="float-right">
    <a class="btn btn-info" 
        title="Add Activity"
        data-href="{{route('myleadsactivity.store')}}" 
        data-toggle="modal" 
        data-target="#add_activity" 
        data-title = "Add activity to lead" 
        href="#">
        <i class="fas fa-pencil-alt"></i>
        Add Activity
        </a>
    </div>
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Date</th>
    <th>Activity</th>
    <th>Contact</th>
    <th>Notes</th>
    <th>Follow-Up</th>
    <th>Actions</th>

    </thead>
    <tbody>
         @foreach($mylead->relatedLeadNotes as $activity)
         
            <tr>
                <td>{{$activity->activity_date ? $activity->activity_date->format('M j, Y'):''}}</td>
                <td>{{$activity->activity}}</td>
                <td>{{$activity->relatedContact ? $activity->relatedContact->contact : ''}}</td>
                <td>{{$activity->note}}</td>
                <td>{{$activity->followup_date ? $activity->followup_date->format('M j, Y') : ''}}</td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">

                        
                        
                        <a class="dropdown-item" 
                        title="Edit Activity"
                          href="{{route('myleadsactivity.edit',$activity->id)}}">
                        <i class="far fa-edit text-info"" aria-hidden="true"> </i>
                        Edit activity</a>
                        
                        <a class="dropdown-item"
                        title="Delete Activity"
                          data-href="{{route('myleadsactivity.destroy',$activity->id)}}" 
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
@include('myleads.partials._activities')
