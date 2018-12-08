<markers>
@foreach($projects as $project)		
<marker
	locationweb="{{route('construction.show', $project['_source']['id'])}}" 
	name="{{trim($project['_source']['siteaddresspartial'])}}"
	address="{{ trim($project['_source']['siteaddress'])}}"
	lat="{{ $project['_source']['location']['lat']}}"
	lng="{{ $project['_source']['location']['lon']}}"
	id="{{ $project['_source']['id']}}"
	
/>
@endforeach
</markers>