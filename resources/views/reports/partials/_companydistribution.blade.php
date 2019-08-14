<h4>NAM of the Following Companies</h4>

<div class="container">
    <div class="float-right">
        <button class="btn btn-success">Add Company</button>
    </div>
<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Manager</th>
    
    </thead>
    <tbody>
        @foreach ($report->companydistribution as $recipient)

        <tr>
            <td>{{$recipient->companyname}}</td>
            <td>{{$recipient->managedBy->fullName()}}</td>
           
        </tr>
        @endforeach
    </tbody>
</table>
</div>