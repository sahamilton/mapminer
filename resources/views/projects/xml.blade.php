<markers>
@foreach($result as $row)
	
<marker
	locationweb="{{route('projects.show',$row->id)}}" 
	prstatus='{{$row->prstatus}}'
	name="{{trim($row->project_title)}}"
	address="{{ trim($row->street)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->project_lat}}"
	lng="{{ $row->project_lng}}"
	id="{{ $row->id}}"
	type='project'

/>
@endforeach
</markers>