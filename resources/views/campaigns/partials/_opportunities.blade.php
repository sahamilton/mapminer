@php
$statuses = ['0'=>'Open', '1'=>"Closed Won", '2'=>'Closed Lost']
@endphp
<table class="table table-striped"
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
       
        @foreach ($data as $opportunity)
        <tr>
          <td>
           
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}}


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
