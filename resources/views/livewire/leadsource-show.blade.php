<div>
    <h2>Lead Source - {{$leadsource->source}}</h2>
    <p><a href="{{route('leadsource.addcompany',$leadsource->id)}}" class="btn btn-success" >Add Existing Company Locations</a></p>
    <p><a href="{{route('leadsource.export',$leadsource->id)}}"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i>  Export owned and closed Leads</a></p>
    <p><a href="{{route('leadsource.index')}}">Return to all lead sources</a></p>
    <p><strong>Editor:</strong> {{$leadsource->author ? $leadsource->author->person->fullName() : ''}}</p>
    <p><strong>Created:</strong> {{$leadsource->created_at->format('M j, Y')}}</p>
    <p><strong>Available From:</strong> {{$leadsource->datefrom->format('M j, Y')}}</p>
    <p><strong>Available Until:</strong> {{$leadsource->dateto->format('M j, Y')}}</p>
    <p><strong>Description:</strong> {{$leadsource->description}}</p>
    <p><strong>Total Leads:</strong> {{$leadsource->leads->count()}}</p>
    <p><strong>Companies:</strong> 

    <table>
        <thead>
            <th>Company</th>
            <th>Leads</th>
        </thead>
        @foreach ($companies as $company)
            <tr>
            <td>{{$company['companyname']}}</td>
            <td>{{$company['count']}}</td>
            </tr>
        
        @endforeach
    </table>
    </p>
    
    <fieldset><legend>Branches</legend>
    
    
    </fieldset>
    <p><strong>Number of UnAssigned Leads:</strong></p>
</div>
