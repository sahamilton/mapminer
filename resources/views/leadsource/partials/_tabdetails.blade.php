<p><strong>Editor:</strong> {{$leadsource->author->person->fullName()}}</p>
<p><strong>Created:</strong> {{$leadsource->created_at->format('M j, Y')}}</p>
<p><strong>Available From:</strong> {{$leadsource->datefrom->format('M j, Y')}}</p>
<p><strong>Available Until:</strong> {{$leadsource->dateto->format('M j, Y')}}</p>
<p><strong>Description:</strong> {{$leadsource->description}}</p>
<p><strong>Total Leads:</strong> {{$leadsource->addresses_count}}</p>
<p><strong>Industry Verticals:</strong> 
<ul>
@foreach ($leadsource->verticals as $vertical)
	<li>{{$vertical->filter}}</li>
@endforeach
</ul>
</p>
<fieldset><legend>Sales Teams</legend>
<p><strong>Number of Leads:</strong>{{number_format($leadsource->leads_count,0)}}</p>
<p><strong>Number of Sales Reps with Leads:</strong>{{number_format(count($teamStats),0)}}</p>
<p><strong>Number of Assigned Leads:</strong>{{number_format($leadsource->assigned_leads_count,0)}}</p>
<p><strong>Number of Closed Leads:</strong>{{number_format($leadsource->closed_leads_count,0)}}</p>
<p><strong>Number of UnAssigned Leads:</strong>{{number_format($leadsource->unassigned_leads_count,0)}}</p>
</fieldset>
<fieldset><legend>Branches</legend>
<p><strong>Number of Branches with Leads:</strong>{{number_format($branches->count(),0)}}</p>
<p><strong>Number of Assigned Leads:</strong>{{number_format($branchStats['assigned'],0)}}</p>
<p><strong>Assignment Ratio:</strong>{{number_format($branchStats['assigned']/$leadsource->addresses_count,0)}}</p>
<p><strong>Number of Closed Leads:</strong>{{number_format($branchStats['closed'],0)}}</p>
</fieldset>
<p><strong>Number of UnAssigned Leads:</strong>{{number_format($leadsource->unassigned_leads_count,0)}}</p>
<a href="{{route('leads.assignbatch',$leadsource->id)}}" class="btn btn-info">Assign Leads</a></p>
<!-- how do we check to see if they are already assigned?-->

