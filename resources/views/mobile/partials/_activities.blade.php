<h4>Nearby Activities</h4>
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>Activity</th>
        <th>Due Date</th>
    </thead>
    <tbody>
        @foreach($results as $result)
        
        <tr>
            <td>
                <a href="{{route('mobile.show',$result->address->id)}}"> 
                    {{$companyname}}
                </a>
            </td>
            <td>{{$result->address->fullAddress()}}</td>
            <td>{{$result->activity}}</td>
            <td>{{$result->activity_date}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>