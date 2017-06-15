
<p><strong>Editor:</strong> {{$leadsource->author->person->postName()}}</p>
<p><strong>Created:</strong> {{$leadsource->created_at->format('M j, Y')}}</p>
<p><strong>Available From:</strong> {{$leadsource->datefrom->format('M j, Y')}}</p>
<p><strong>Available Until:</strong> {{$leadsource->dateto->format('M j, Y')}}</p>
<p><strong>Description:</strong> {{$leadsource->description}}</p>
<p><strong>Number of Leads:</strong>{{count($leadsource->leads)}}</p>
<!-- how do we check to see if they are already assigned?-->
@if(! $salesteams)
<p><a href="{{route('leads.geoassign',$leadsource->id)}}"><button class="btn btn-info"  > Assign Leads Geographically</button></a></p>
@endif