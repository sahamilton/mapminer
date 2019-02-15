
<table id ='sorttable6' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

        <th>Date Opened</th>
        <th>Status</th>
        <th>Branch</th>
        <th>Title</th>
        <th>Potential Headcount</th>
        <th>Potential Duration</th>
        <th>Potential $$</th>
        <th>Description</th>
        <th>Comments</th>
        <th>Created By</th>

    </thead>
    <tbody>
        @foreach ($location->opportunities as $opportunity)
    
        <tr>
            <td>{{$opportunity->created_at->format('Y-m-d')}}</td>
            <td>{{$opportunity->closed}}</td>
            <td>{{$opportunity->address->branch->branchname}}</td>
            <td>{{$opportunity->title}}</td>
            <td>{{$opportunity->requirements}}</td>
            <td>{{$opportunity->duration}}</td>
            <td>${{number_format($opportunity->value,0)}}</td>
            <td>{{$opportunity->description}}</td>
            <td>{{$opportunity->comments}}</td>
            <td>
                @if($opportunity->createdBy)
                    {{$opportunity->createdBy->person->fullName()}}
                @endif
            </td>


        </tr>
        @endforeach
    </tbody>

</table>