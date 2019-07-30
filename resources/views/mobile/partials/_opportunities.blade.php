<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Opportunity</th>
        <th>Value</th>
        <th>Expected Close</th>
        <th>Distance</th>
        <th>Last Activity</th>
    </thead>
    <tbody>
        @foreach($results as $result)
        
        <tr>
            
            <td><a href="{{route('mobile.show',$result->address_id)}}">{{$result->title}}</a></td>
            <td>{{$result->value}}</td>
            <td>{{$result->expected_close}}</td>
            <td>{{number_format($result->distance,2)}} mi</td>
            <td>
                @if($result->address->address->lastActivity->count() >0)
                    {{$result->address->address->lastActivity->first()->activity_date->format('Y-m-d')}}
                @endif
                <br /><a 
                    data-href="" 
                    data-id="{{$result->address_id}}"
                           data-toggle="modal" 
                           data-target="#add_activity" 
                           data-title = "{{$result->businessname}}" 
                           href="#">

               <i class="fas fa-plus-circle text-success"></i>Add Activity</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
