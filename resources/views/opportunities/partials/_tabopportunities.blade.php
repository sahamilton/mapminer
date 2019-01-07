<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Business</th>
      <th>Address</th>
      <th>Potential $$</th>
      <th>Potential Labor Reqts</th>
      <th>Last Activity</th>
      <th>Activities</th>
    </thead>
      <tbody>
        @foreach ($opportunities as $opportunity)
      
        <tr>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}</td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>{{$opportunity->closed}}</td>
          <td>
            <a href= "{{route('opportunity.show',$opportunity->id)}}">
              {{$opportunity->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->fullAddress()}}</td>
          <td>{{$opportunity->value}}</td>
          <td>{{$opportunity->requirements}}</td>
          <td>
            @if($opportunity->address->activities->count() >0 )
              {{$activityTypes[$opportunity->address->activities->last()->activity]}}
             <br />
            {{$opportunity->address->activities->last()->activity_date->format('Y-m-d')}}
            @endif
          </td>
          <td>
              <a 
                  data-href="{{route('activity.store')}}" 
                  data-toggle="modal" 
                  data-pk = "{{$opportunity->address->id}}"
                  data-id="{{$opportunity->address->id}}"
                  data-target="#add-activity" 
                  data-title = "location" 
                  href="#">
              <i class="fa fa-plus-circle text-success" aria-hidden="true"></i> Add Activity</a>

          </td>
        </tr>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>