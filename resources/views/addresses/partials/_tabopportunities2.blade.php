
@php $statuses = ['open','closed - won','closed - lost']; @endphp
@if($owned)
<div class="float-right">
<button class="btn btn-success" 
      data-toggle="modal" 
      data-target="#createopportunity">New Opportunity</button>
      @include('opportunities.partials._createmodal')

</div>
@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Title</th>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Company</th>
      <th>Address</th>
      <th>Top 25</th>
      <th>Potential Headcount</th>
      <th>Potential Duration (mos)</th>
      <th>Potential $$</th>
      
      <th>Last Activity</th>
      <th>Activities</th>
    </thead>
      <tbody>
        @foreach ($data['opportunities'] as $opportunity)
         
        <tr>
          <td>
           @if($owned)
            
            <a href="{{route('opportunity.show',$opportunity->id)}}" title="Review, edit or delete this opportunity">
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}} </a>
          
              @else
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}}

              @endif
              @if($opportunity->csp == 1)
                  <p class="text-success"><i class="fas fa-clipboard-list "></i> CSP Opportunity</p>
              @endif
          </td>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}
          </td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>

            {{$statuses[$opportunity->closed]}}
          
             @if($owned && $opportunity->closed ==0)
           
            <button class="btn btn-danger" 
                    data-href="{{route('opportunity.close',$opportunity->id)}}"
                    data-toggle="modal" 
                    data-target="#closeopportunity">Close</button>
            @endif
          </td>
          <td>
          
            <a href= "{{route('address.show',$opportunity->address->address->id)}}">
              {{$opportunity->address->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->address->fullAddress()}}</td>
          <td>

            <input type="checkbox" id="Top25{{$opportunity->id}}" value="{{$opportunity->id}}" 
            @if($opportunity->Top25)
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
            @if(isset($data['branches']) && in_array($data['branches']->first()->id,array_keys($myBranches)))
              <a 
                  data-href="{{route('activity.store')}}" 
                  data-toggle="modal" 
                  data-pk = "{{$opportunity->address->id}}"
                  data-id="{{$opportunity->address->id}}"
                  data-target="#add-activity" 
                  data-title = "location" 
                  href="#">
              <i class="fa fa-plus-circle text-success" aria-hidden="true"></i> Add Activity</a>
              @endif
          </td>
        </tr>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>


<script>
$( document ).ready(function() {
    $("input[id^=Top25]").change (function () {
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