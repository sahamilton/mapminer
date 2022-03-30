
@php $statuses = ['open','closed - won','closed - lost']; @endphp

<div class="float-right">
        <a class="btn btn-info" 
            title="Add Opportunity"
             
            data-toggle="modal" 
            data-target="#createopportunity" 
            data-title = "Create New Opportunity at {{$address->businessname}}" 
            href="#">
            <i class="fas fa-pencil-alt"></i>
            New Opportunity
            </a>
    </div>
   <div class="col form-inline">
    @include('livewire.partials._perpage')
   
    @include('livewire.partials._search', ['placeholder'=>'Search activities'])
    
</div>           
                  
<table class='table table-striped table-bordered table-condensed table-hover'>
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
      
    </thead>
      <tbody>
        @foreach ($viewdata as $opportunity)
         
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