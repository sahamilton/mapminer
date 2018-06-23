<h2>Lead Details</h2>
<div id="map-container">
	<div style="float:left;width:300px">
		<p><strong>Vertical: </strong>{{$lead->Primary_Industry}}</p>
		<p><strong>Revenue: </strong>@if($lead->Revenue != '') ${{number_format($lead->Revenue,1)}}M @endif</p>
		<p><strong>Business Type:</strong> {{$lead->Line_Of_Business}}</p>
		<p><i>A location of {{$lead->Ultimate_Parent}}</i></p>
		<p><strong>Lead Source:</strong> {{$lead->leadsource->source}}</i></p>
		
		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			<p>
			<i class="fa fa-user" aria-hidden="true"></i>
			 <b>Primary Contact:</b> {{$lead->contacts->contact}}</p>
			 <b>Title:</b> {{$lead->contacts->contacttitle}}</p>
			<p>
			<i class="fa fa-map-marker" aria-hidden="true"></i>
			 <b>Address:</b><br/>{{$lead->Primary_Address}}<br />{{$lead->Primary_City}}  {{$lead->Primary_State}} {{$lead->Primary_Zip}}</p>
			<p><b><i class="fa fa-phone" aria-hidden="true"></i> Phone:</b> {{$lead->contacts->contactphone}}</p>
			@if(! empty($lead->contacts->contactemail))
			<p><b><i class="fa fa-envelope" aria-hidden="true"></i> Email:</b> <a href="mailto:{{$lead->contacts->contactemail}}">{{$lead->contacts->contactemail}}</a></p>
			@endif
			 
		 </fieldset>
		 
		<p>
		
		
			<i class="fa fa-location-arrow" aria-hidden="true"></i>
			<b>Closest Branch: </b>
			@if(count($branches)>0)
			<a href="{{ route('branches.show', $branches->first()->id) }}" 
			title='show  {{trim($branches->first()->branchname)}} details'>
			{{$branches->first()->id}}:{{$branches->first()->branchname}} </a>
			@endif
		 
		
		</p>
		
		
		<hr />
	</div>
	 <div id="map" style="height:300px;width:500px;border:red solid 1px">
	</div>
</div>