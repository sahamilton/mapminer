@php $statuses = ['open','closed - won','closed - lost']; @endphp

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
      <th>Expected Close</th>
      <th>Last Activity</th>
      <th>Activities</th>
    </thead>
      <tbody>
        @foreach ($data['opportunities'] as $opportunity)

        @if($opportunity->closed == 0)
       
        <tr>
          <td>
            @if(isset($data['branches']) && in_array($data['branches']->first()->id,array_keys($myBranches)))
            <a href="{{route('opportunity.show',$opportunity->id)}}" title="Review, edit or delete this opportunity">
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}} <i class="fas fa-edit text text-info"></i></a>
            @else
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}}

              @endif
            @if($opportunity->csp == 1)
                <p class="text-success">
                  <i class="fas fa-clipboard-list "></i> 
                  CSP Opportunity
                </p>
              @endif
          </td>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}
          </td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>

            {{$statuses[$opportunity->closed]}}
            @if(isset($data['branches']) &&  $opportunity->closed == 0 && in_array($data['branches']->first()->id,array_keys($myBranches)))
            <button class="btn btn-danger" 
                    data-href="{{route('opportunity.close',$opportunity->id)}}"
                    data-toggle="modal" 
                    data-target="#closeopportunity">Close</button>
            @endif
          </td>
          <td>
            <a href= "{{route('address.show',$opportunity->address->address->id)}}">{{$opportunity->address->address->businessname}}</a>
          </td>
          <td>{{$opportunity->address->address->fullAddress()}}</td>
          <td>
            
            <input 
              type="checkbox" 
              class="Top25" 
              value="{{$opportunity->id}}" 
              @if($opportunity->Top25)
                checked
              @endif />
            <span class="d-none">{{$opportunity->Top25}}</span>
            
            
          </td>
          <td>{{$opportunity->requirements}}</td>
          <td>{{$opportunity->duration}}</td>
          <td>{{$opportunity->value}}</td>
                    <td>
            @if($opportunity->expected_close )
            {{$opportunity->expected_close->format('Y-m-d')}}
            @if($opportunity->expected_close->diff(now())->m > 1)
              <br /><i class="fas fa-exclamation-triangle text-danger" title="Stale Opportunity!"></i>
            @endif
            @endif
          </td>
          
          <td>
            @if($opportunity->address->address->lastActivity)

            {{$opportunity->address->address->lastActivity->activity_date->format('Y-m-d')}}
              @if($opportunity->address->address->lastActivity->activity_date->diff(now())->m > 1)
                <br /><i class="fas fa-exclamation-triangle text-danger" title="Stale Account!"></i>
              @endif
            
            @endif
          </td>
          
          <td>
   
            @if(isset($data['branches']) && in_array($data['branches']->first()->id,array_keys($myBranches)))
              <a 
                  data-href="{{route('activity.store')}}" 
                  data-toggle="modal" 
                  data-pk = "{{$opportunity->address->address->id}}"
                  data-id="{{$opportunity->address->address->id}}"
                  data-target="#add-activity" 
                  data-title = "location" 
                  href="#">
              <i class="fa fa-plus-circle text-success" aria-hidden="true"></i> Add Activity</a>
              @endif
  
          </td>
        </tr>
        @endif
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>


<script>
$( document ).ready(function() {
    $(".Top25").change (function () {
      
      var id = $(this).val();

      $.ajax(
    
        {
        
        type: "get",
               
        url: '{{route("opportunity.toggle")}}',
        
        cache: false,
        
        data: {id: id,api_token:"{{auth()->user()->api_token}}"},
        
        dataType: "xml",
        
        contentType: "json",
        
        success: function(msg){
                    if(msg =='success'){
                        alert('Success');
                    } 
                    else{
                        alert('Fail');
                    }
               }
        
        }); 
    });
});

</script>