<p><strong>Type:</strong>{{$location->project->project_type}}</p>


<p><strong>Source:</strong>{{$location->project->source->source}}</p>
<p><strong>Source ref #:</strong>{{$location->project->id}}</p>

<p><strong>Category:</strong>
{{$location->project->structure_header}} / {{$location->project->project_type}}</p>
<p><strong>Stage:</strong>{{$location->project->stage}}</p>
<p><strong>Ownership:</strong>{{$location->project->ownership}}</p>
<p><strong>Bid Date:</strong>{{$location->project->bid_date}}</p>
<p><strong>Project Start:</strong>{{$location->project->start_yearmo}}</p>
<p><strong>Target Start:</strong>{{$location->project->target_start_date}}</p>
<p><strong>Target Completion:</strong>{{$location->project->target_comp_date}}</p>
<p><strong>Work type:</strong>{{$location->project->work_type}}</p>
<p><strong>Project Status:</strong>{{$location->project->status}}</p>

<p><strong>Total Project Value:</strong>
	@if(isset($location->project->total_project_value))
		${{number_format($location->project->total_project_value/1000,1)}}k
	@endif
</p>