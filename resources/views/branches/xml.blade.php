<markers>
@foreach ($branches as $branch)

<marker 
name = "{{$branch->branchname}}" 
address="{{trim($branch->street)}} {{trim($branch->address2)}}, {{trim($branch->city)}} {{trim($branch->state)}}" 
lat="{{$branch->lat}}" 
lng="{{$branch->lng}}" 
locationweb="{{route('branches.show',$branch->id)}}" 
id="{{$branch->id}}" 
type="branch" 
brand="{{$branch->servicelines[0]->ServiceLine}}" 
color="{{$branch->servicelines[0]->color}}"
/>
@endforeach

</markers>