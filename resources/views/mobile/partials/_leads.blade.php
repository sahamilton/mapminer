<h4>Nearby Leads</h4>
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
            <td><a href="{{route('address.show',$result->address_id)}}">{{$result->businessname}}</a></td>
            <td>{{$result->fullAddress()}}</td>
            <td>{{number_format($result->distance,2)}} mi</td>
            <td>
                @if($result->lastActivity->count() > 0)

                    {{$result->lastActivity->first()->activity_date->format('Y-m-d')}} 
                @endif
            </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>