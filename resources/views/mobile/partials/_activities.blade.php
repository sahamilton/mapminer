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
        {{dd($result)}}
        <tr>
            <td>{{$companyname}}</td>
            <td>{{$fullAddress()}}</td>
            <td>{{$activity}}</td>
            <td>{{$duedate}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>