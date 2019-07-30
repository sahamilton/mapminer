
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>Distance</th>
        <th>Last Activity</th>
 
        
    </thead>
    <tbody>
        @foreach($results as $result)
      

        <tr>
            <td><a href="{{route('mobile.show',$result->address_id)}}">{{$result->businessname}}</a></td>
            <td>{{$result->fullAddress()}}</td>
            <td>{{number_format($result->distance,2)}} mi</td>
            <td>
                @if($result->lastActivity->count() > 0)

                    {{$result->lastActivity->first()->activity_date->format('Y-m-d')}} 
                @endif
               <br />
               @if(isset($result->address_id))<a 
                    data-href="" 
                    data-id="{{$result->address_id}}"
                           data-toggle="modal" 
                           data-target="#add_activity" 
                           data-title = "{{$result->businessname}}" 
                           href="#">

               <i class="fas fa-plus-circle text-success"></i>Add Activity</a>
               @endif
            </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>