<div class="container">
<table id ='responsive5'  class="display responsive no-wrap" width="100%">
    <thead>
        <th>Opportunity</th>
        <th>Address</th>
        <th>Expected Close</th>
        <th>Distance</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($results as $result)
        
        <tr>
            
            <td><a href="{{route('mobile.show',$result->address_id)}}">{{$result->title}}</a></td>
            <td>{{$result->address->address->fullAddress()}}</td>
            <td>
                @if($result->expected_close)
                    {{$result->expected_close->format('Y-m-d')}}
                @endif
            </td>
            <td>{{number_format($result->distance,2)}} mi</td>
            <td>
                @if($result->address->address->lastActivity)
                    {{$result->address->address->lastActivity->activity_date->format('Y-m-d')}}
                @endif
                <br /><a 
                    data-href="" 
                    data-id="{{$result->address_id}}"
                           data-toggle="modal" 
                           data-target="#add_activity" 
                           data-title = "{{$result->businessname}}" 
                           href="#">

               <i class="fas fa-plus-circle text-success"></i>Add Activity </a>
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>
