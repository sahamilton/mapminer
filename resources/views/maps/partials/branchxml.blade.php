<markers>
@foreach ($branches as $branch)

<marker 
    	name="{{$branch->branchname}}" 
	    address="{{trim($branch->street)}}{{$branch->address2}},{{$branch->city}} {{$branch->state}}  {{$branch->zip}}" 
	    lat="{{$branch->lat}}" 
	    lng="{{$branch->lng}}" 
	    locationweb="{{route('branches.show',$branch->id)}}"
	    type="branch" 
	
	  />
@endforeach
</markers>

