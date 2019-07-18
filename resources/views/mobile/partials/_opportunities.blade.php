<h4>Nearby Opportunities</h4>
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>Opportunity</th>
        <th>Value</th>
        <th>Expected Close</th>
        <th>Distance</th>
        <th>Last Activity</th>
    </thead>
    <tbody>
        @foreach($results as $result)
        
        <tr>
            <td><a href="{{route('address.show',$result->address_id)}}">{{$result->address->address->businessname}}</a></td>
            <td>{{$result->address->address->fullAddress()}}</td>
            <td>{{$result->title}}</td>
            <td>{{$result->value}}</td>
            <td>{{$result->expected_close}}</td>
            <td>{{number_format($result->distance,2)}} mi</td>
            <td>
                @if($result->address->address->lastActivity->count() >0)
                    {{$result->address->address->lastActivity->first()->activity_date->format('Y-m-d')}}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>