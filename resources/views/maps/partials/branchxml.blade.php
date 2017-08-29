<markers xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($branches as $branch)
<marker 
    	name="{{$branch->branchname}}" 
	    address="{{trim($branch->street)}}{{$branch->address2}},{{$branch->city}} {{$branch->state}}  {{$branch->zip}}" 
	    lat="{{$branch->lat}}" 
	    lng="{{$branch->lng}}" 
	    locationweb="{{route('branches.show',$branch->branchid)}}"
	    type="branch" 
	
	  />
@endforeach
</markers>

