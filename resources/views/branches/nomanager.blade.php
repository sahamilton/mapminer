<table>
    <thead>
        <th colspan="3">
        {{$title}}
        </th>
        <tr>
            <th>Branch</th>
            <th>City</th>
            <th>State</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($result as $branch)
        <tr>
            <td>{{$branch->branchname}}</td>
            <td>{{$branch->city}}</td>
            <td>{{$branch->state}}</td>
        </tr>
        @endforeach
    </tbody>
</table>