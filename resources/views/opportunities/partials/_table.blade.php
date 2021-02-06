@php $statuses = ['open','closed - won','closed - lost']; @endphp

    @foreach ($opportunities as $opportunity)


   
    <tr>
      <td>
        
        <a href="{{route('opportunity.show',$opportunity->id)}}" title="Review, edit or delete this opportunity">
        {{$opportunity->title ?  $opportunity->title : $opportunity->id}} <i class="fas fa-edit text text-info"></i></a>
        
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

        {{ucwords($statuses[$opportunity->closed])}}
        @if($opportunity->closed == 0 && auth()->user()->hasRole('branch_manager'))
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
          @if($opportunity->closed !=0 || ! auth()->user()->hasRole('branch_manager'))
          disabled
          @endif
          value="{{$opportunity->id}}" 
          @if($opportunity->Top25)
            checked
          @endif />
        <span class="d-none">{{$opportunity->Top25}}</span>
        
        
      </td>
      <td style="text-align:center">{{$opportunity->requirements}}</td>
      <td style="text-align:center">{{$opportunity->duration}}</td>
      <td style="text-align:right">
        ${{number_format($opportunity->value, 0)}}
      </td>
      <td>
        @if($opportunity->expected_close )
        {{$opportunity->expected_close->format('Y-m-d')}}
        @if($opportunity->expected_close->diff(now())->m > 1 && $opportunity->closed==0)
          <br /><i class="fas fa-exclamation-triangle text-danger" title="Stale Opportunity!"></i>
        @endif
        @endif
      </td>
          <td>
            {{$opportunity->actual_close ? $opportunity->actual_close->format('Y-m-d') : ''}}
      </td>
      <td>
        @if($opportunity->lastActivity)

        {{$opportunity->lastActivity->activity_date->format('Y-m-d')}}
          @if($opportunity->lastActivity->activity_date->diff(now())->m > 1 && $opportunity->closed==0)
            <br /><i class="fas fa-exclamation-triangle text-danger" title="Stale Account!"></i>
          @endif
        
        @endif
      </td>
          @if(auth()->user()->hasRole('branch_manager'))
      <td>

        
          <a 
            data-href="{{route('activity.store')}}" 
            data-toggle="modal" 
            data-pk = "{{$opportunity->address->address->id}}"
            data-id="{{$opportunity->address->address->id}}"
            data-branch_id="{{$branch->id}}"
            data-target="#add-activity" 
            data-title = "location" 
            href="#">
          <i class="fa fa-plus-circle text-success" aria-hidden="true"></i> Add Activity</a>
        

      </td>
      @endif
    </tr>
       
        @endforeach

  </tbody>
  <tfoot>
    <tr>
      <th colspan=9></th>
      <th style="text-align:right">${{number_format($opportunities->where('closed', 0)->sum('value'),0)}}
      </th>
      <th colspan=3></th>
    </tr>

  </tfoot>


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