<markers>
@foreach($result as $row)
	
<marker
	locationweb="{{route('projects.show',$row->id)}}" 
	prstatus='{{$row->prstatus}}'
	name="{{trim($row->project_title)}}"
	address="{{ trim($row->street)}} {{trim($row->city)}} {{ trim($row->state)}}"
	lat="{{ $row->lat}}"
	lng="{{ $row->lng}}"
	id="{{ $row->id}}"
	type='project'

/>
@endforeach
</markers>