<h4>Recipients</h4>

<div class="container">
    <div class="float-right">
        <button class="btn btn-success">Add Email</button>
    </div>
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
</div>