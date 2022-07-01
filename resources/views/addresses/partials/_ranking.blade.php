<div class="float-right">

@if($ranked)
	You rated this location <span style="font-size:150%" class='starrr2 text text-success col-md-6' id="rank" > </span>.
	<script>
	
	$('.starrr2').starrr({
  readOnly: true,
  
  	rating:{{number_format($ranked->ranking,0)}},
  
})
</script>
@else

<button type="button" 
		class="btn btn-info float-right" 
		data-toggle="modal" 
		data-target="#rateaddress">
			Rate Location Data
</button>
@endif

@if(!$address->currentRating())
	
This location has not been rated yet.
@endif
</div>


