<h4>Recipients</h4>
<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
    </thead>
    <tbody>
        @foreach ($report->distribution as $recipient)

        <tr>
            <td>
                <a 
                    data-href="{{route('reports.removerecipient', $report->id)}}" 
                    data-toggle="modal" 
                    data-target="#remove-recipient" 
                    data-title = " {{$recipient->fullName()}} from {{$report->report}} report" 
                    data-pk="{{$recipient->id}}"
                    href="#"> 
                    
                    <i class="fas fa-trash-alt text-danger"> </i>
                </a>
                {{$recipient->fullName()}}</td>
            <td>{{$recipient->email}}</td>
            <td>
                @foreach ($recipient->roles as $role)
                <li>{{$role->display_name}}</li>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>