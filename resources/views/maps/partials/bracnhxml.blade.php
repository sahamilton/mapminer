<?xml version="1.0" encoding="UTF-8"?>
<markers xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($branches as $branch)

    <marker 
    	name="{{$branch->branchname}}" 
	    address="{{$branch->fullAddress()}}" 
	    lat="{{$branch->lat}}" 
	    lng="{{$branch->lng}}" 
	    locationweb="{{route('branches.show",$branch->id)}}"
	    type="branch" 
	    brand="{{$branch->serviceline}}"
	  />
@endforeach
</marker>

