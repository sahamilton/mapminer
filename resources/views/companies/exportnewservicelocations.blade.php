<?php $limit = 5;?>
	<table>
        <tbody>
		<tr>
			<td>Business Name</td>
			<td>Street</td>
			<td>City</td>
			<td>State</td>
			<td>ZIP</td>
			@for($i=1;$i<$limit+1;$i++)
				<td>Branch {{$i}}</td>
				<td>Branch {{$i}} Address</td>
				<td>Branch {{$i}} Phone</td>
				<td>Branch {{$i}} Email</td>
				<td>Branch {{$i}} Proximity (miles)</td>
			@endfor
			@for($i=1;$i<$limit+1;$i++)
				<td>Reps {{$i}}</td>
				<td>Reps {{$i}} Phone</td>
				<td>Reps {{$i}} Email</td>
				<td>Reps {{$i}} Proximity (miles)</td>
			@endfor		
		</tr>

	   @foreach($locations as $location)
	    <tr> 
			<td>{{$location['location']['businessname']}}</td>
			<td>{{$location['location']['street']}}</td>
			<td>{{$location['location']['city']}}</td>
			<td>{{$location['location']['state']}}</td>
			<td>{{$location['location']['zip']}}</td>
			<?php usort($location['branch'], function ($a, $b) { return $a['distance'] - $b['distance']; });
				$branchcount = count($location['branch']);?>
				@foreach ($location['branch'] as $branch)
					<td>{{$branch['branchname']}}</td>
					<td>{{$branch['address']}}</td>
					<td>{{$branch['phone']}}</td>
					<td>Email</td>
					<td>{{number_format($branch['distance'],1)}}</td>
				@endforeach
				@for($i=0;$i<$limit-$branchcount;$i++)
					<td></td><td></td><td></td><td></td><td></td>

				@endfor

				<?php usort($location['rep'], function ($a, $b) { return $a['distance'] - $b['distance']; });
				$repcount = count($location['rep']);?>
				@foreach ($location['rep'] as $rep)
				
					<td>{{$rep['repname']}}</td>
					<td>{{$rep['phone']}}</td>
					<td>Email</td>
					<td>{{number_format($rep['distance'],1)}} mi </td>		
					
				@endforeach
				@for($i=0;$i<$limit-$repcount;$i++)
					<td></td><td></td><td></td><td></td><td></td>

				@endfor
			<td>
	    </tr>
	   @endforeach
    </tbody>
</table>