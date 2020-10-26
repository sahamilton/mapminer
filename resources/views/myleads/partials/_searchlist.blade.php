<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Date Created</th>
        <th>Source</th>
    </thead>
    <tbody>
        @foreach($leads as $lead)
            <tr>
                <td><a href="{{route('address.show', $lead->id)}}">{{$lead->businessname}}</a></td>
                <td>{{$lead->street}}</td>
                <td>{{$lead->city}}</td>
                <td>{{$lead->state}}</td>
                <td>{{$lead->created_at->format('M j, Y')}}</td>
                <td>{{$lead->leadsource->source}}</td>
            </tr>
           @endforeach
    </tbody>
</table>
   
