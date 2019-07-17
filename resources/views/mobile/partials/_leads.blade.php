<h4>Nearby Activities</h4>
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>Distance</th>
        
    </thead>
    <tbody>
        @foreach($results as $result)

        <tr>
            <td><a href="{{route('address.show',$result->address_id)}}">{{$result->businessname}}</a></td>
            <td>{{$result->fullAddress()}}</td>
            <td>{{number_format($result->distance,2)}} mi</td>
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>