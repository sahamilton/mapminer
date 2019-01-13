@php $statuses = ['open','closed - won','closed - lost']; @endphp
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Business</th>
      <th>Address</th>
      <th>Top 50</th>
      <th>Potential Headcount</th>
      <th>Potential Duration (mos)</th>
      <th>Potential $$</th>
      
      <th>Last Activity</th>
      <th>Activities</th>
    </thead>
      <tbody>
        @foreach ($data['opportunities'] as $opportunity)
      
        <tr>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}</td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>{{$statuses[$opportunity->closed]}}</td>
          <td>
            <a href= "{{route('opportunity.show',$opportunity->id)}}">
              {{$opportunity->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->fullAddress()}}</td>
          <td>

            <input type="checkbox" id="top50{{$opportunity->id}}" value="{{$opportunity->id}}" 
            @if($opportunity->top50)
            checked/><span class="hidden">1</span>
            @endif
            
          </td>
          <td>{{$opportunity->requirements}}</td>
          <td>{{$opportunity->duration}}</td>
          <td>{{$opportunity->value}}</td>
          <td>
            @if($opportunity->address->activities->count() >0 )

              {{$opportunity->address->activities->last()->activity_id}}
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
@include('opportunities.partials._activitiesmodal')
<script>
$( document ).ready(function() {
    $("input[id^=top50]").change (function () {
      var id = $(this).val();

      $.ajax(
    
        {
        
        type: "get",
        
        cache: false,
        
        url: '{{route("opportunity.toggle")}}',

        data: {id: id,api_token:"{{auth()->user()->api_token}}"},
        
        dataType: "xml",
        
        contentType: "json",
        
        success: true
        
        }); 
    });
});

</script>