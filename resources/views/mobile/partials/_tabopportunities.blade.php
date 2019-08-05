<a 
    class="btn btn-info" 
    title="Add Opportunity"
    href=""
    data-toggle="modal" 
    data-target="#createopportunity">
      <i class="fas fa-file-invoice-dollar"></i> 
  Create New Opportunity
</a>
<table id ='sorttable6' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

        <th>Date Opened</th>
        <th>Change Status</th>
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
        @foreach ($address->opportunities as $opportunity)

        <tr>
            <td>{{$opportunity->created_at->format('Y-m-d')}}</td>
            <td>

            <button class="btn btn-danger" 
                    data-href="{{route('opportunity.close',$opportunity->id)}}"
                    data-toggle="modal" 
                    data-target="#closeopportunity">Close</button>
            </td>
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
@include('opportunities.partials._closemodal')
@include('mobile.partials._createopportunitymodal')
