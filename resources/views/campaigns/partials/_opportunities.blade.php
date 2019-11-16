@php
$statuses = ['0'=>'Open', '1'=>"Closed Won", '2'=>'Closed Lost']
@endphp
<table id="sorttable{{$loop->index}}"
    class="table table-striped"
    >
    <thead>
      <th>Title</th>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Company</th>
      <th>Address</th>
      <th>Potential $$</th>
      <th>Last Activity</th>

    </thead>
    <tbody>
       
        @foreach ($branch->openOpportunities as $opportunity)
        <tr>
          <td>
           @if(isset($location) && array_intersect(array_keys($myBranches),$location->assignedToBranch->pluck('id')->toArray()) or auth()->user()->hasRole(['admin', 'sales_operations']))
            
            <a href="{{route('opportunity.show',$opportunity->id)}}" title="Review, edit or delete this opportunity">
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}} <i class="fas fa-edit class="text text-info"></i></a>
          
              @else
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}}

              @endif
          </td>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}
          </td>
          <td>{{$opportunity->daysOpen()}}</td>
        
          <td>
          
            <a href= "{{route('address.show',$opportunity->address->address->id)}}">
              {{$opportunity->address->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->address->fullAddress()}}</td>
         

          <td>${{number_format($opportunity->value,2)}}</td>
          <td>
            @if($opportunity->address->activities->count() >0 )

              {{$opportunity->address->activities->where('completed', 1)->last()->activity_id}}
             <br />
            {{$opportunity->address->activities->where('completed',1)->last()->activity_date->format('Y-m-d')}}
            @endif
          </td>
          
        </tr>
        @endforeach
    </tbody>
</table>
