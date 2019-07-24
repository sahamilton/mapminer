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
            <td>{{$recipient->fullName()}}</td>
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