<p><strong>Type:</strong>{{$project->project_type}}</p>



<p><strong>Source ref #:</strong>{{$project->id}}</p>

<p><strong>Category:</strong>
{{$project->structure_header}} / {{$project->project_type}}</p>
<p><strong>Stage:</strong>{{$project->stage}}</p>
<p><strong>Ownership:</strong>{{$project->ownership}}</p>
<p><strong>Bid Date:</strong>{{$project->bid_date}}</p>
<p><strong>Project Start:</strong>{{$project->start_yearmo}}</p>
<p><strong>Target Start:</strong>{{$project->target_start_date}}</p>
<p><strong>Target Completion:</strong>{{$project->target_comp_date}}</p>
<p><strong>Work type:</strong>{{$project->work_type}}</p>
<p><strong>Project Status:</strong>{{$project->status}}</p>

<p><strong>Total Project Value:</strong>
	@if(isset($project->total_project_value))
		${{number_format($project->total_project_value/1000,1)}}k
	@endif
</p>