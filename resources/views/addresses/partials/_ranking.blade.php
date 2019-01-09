<p>

@if($ranked)
	You rated this location <span style="font-size:150%" class='starrr2 text text-success col-md-6' id="rank" > </span>.
	<script>
	
	$('.starrr2').starrr({
  readOnly: true,
  
  	rating:{{number_format($ranked->ranking,0)}},
  
})
</script>
@else
@include('addresses.partials._addressaction')
@endif

@if($location->currentRating())
	The average ranking for this location is 
	<span style="font-size:150%" class='starrr1 text text-danger col-md-6' id="rank" > </span></p>
	<script>
		$('.starrr1').starrr({
	  		readOnly: true,
	  		rating:{{number_format($location->currentRating(),0)}},
		})
	</script>
@else
This location has not been rated yet.
@endif


