
@if(isset($data['weekcount']) && array_key_exists(auth()->user()->id,$data['weekcount']))
  <div class="alert alert-success">
    <p>{{auth()->user()->person->firstname}}, you have recorded {{$data['weekcount'][auth()->user()->id]}} activities this week.</p>
  </div>
@endif



 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Date</th>
    <th>Activity</th>
    <th>Business</th>
    <th>Contact</th>
    <th>Notes</th>
    <th>Follow-Up</th>
    <th>Actions</th>

    </thead>
    <tbody>
         @foreach($data['branches'] as $branch)
       
           @foreach ($branch->activities as $activity)
  
            <tr>
                <td>{{$activity->activity_date ? $activity->activity_date->format('M j, Y'):''}}</td>
                <td>@if($activity->type)
                    {{$activity->type->activity}}
                    @endif
                </td>
                <td>
                  <a href="{{route('address.show',$activity->relatesToAddress->id)}}">{{$activity->relatesToAddress->businessname}}</a>
                </td>
                <td>@foreach($activity->relatedContact as $contact)
                    <li>{{$contact->fullname}}</li>
                    @endforeach
                    
                </td>
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
                          href="{{route('activity.edit',$activity->id)}}">
                        <i class="far fa-edit text-info"" aria-hidden="true"> </i>
                        Edit activity</a>
                        
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
           @endforeach
    </tbody>
</table>

