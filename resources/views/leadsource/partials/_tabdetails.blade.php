
<p><strong>Editor:</strong> {{$leadsource->author->person->fullName()}}</p>
<p><strong>Created:</strong> {{$leadsource->created_at->format('M j, Y')}}</p>
<p><strong>Available From:</strong> {{$leadsource->datefrom->format('M j, Y')}}</p>
<p><strong>Available Until:</strong> {{$leadsource->dateto->format('M j, Y')}}</p>
<p><strong>Description:</strong> {{$leadsource->description}}</p>
<p><strong>Industry Verticals:</strong> 
<ul>
@foreach ($leadsource->verticals as $vertical)
	<li>{{$vertical->filter}}</li>
@endforeach
</ul>
</p>
<p><strong>Number of Prospects:</strong>{{number_format($leadsource->leads_count,0)}}</p>
<p><strong>Number of Sales Reps with Leads:</strong>{{number_format(count($teamStats),0)}}</p>
<p><strong>Number of Assigned Prospects:</strong>{{number_format($leadsource->assigned_leads_count,0)}}</p>
<p><strong>Number of Closed Prospects:</strong>{{number_format($leadsource->closed_leads_count,0)}}</p>
<p><strong>Number of UnAssigned Prospects:</strong>{{number_format($leadsource->unassigned_leads_count,0)}}
<a href="{{route('leads.assignbatch',$leadsource->id)}}" class="btn btn-info">Assign Prospects</a></p>
<!-- how do we check to see if they are already assigned?-->

