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

    </thead>
    <tbody>
         @foreach($mylead->relatedLeadNotes as $activity)
         
            <tr>
                <td>{{$activity->activity_date ? $activity->activity_date->format('M j, Y'):''}}</td>
                <td>{{$activity->activity}}</td>
                <td>{{$activity->relatedContact ? $activity->relatedContact->contact : ''}}</td>
                <td>{{$activity->note}}</td>
                <td>{{$activity->followup_date ? $activity->followup_date->format('M j, Y') : ''}}</td>
            </tr>
           @endforeach

    </tbody>
</table>
@include('myleads.partials._activities')
