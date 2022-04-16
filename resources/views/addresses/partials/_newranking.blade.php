<div x-data="{


	@if($ranked)

		Location rated 
		@for($i=1;$i<=5; $i++)
			@if($i <= $location->currentRating())
				<a href="" wire:click.prevent="ranking($i)"><i class="text-success fa-solid fa-star"></i></a>
			@else
				<i class="test-secondary fa-solid fa-star"></i>
			@endif
			
			
		@endfor
	@else

	<button type="button" 
			class="btn btn-info float-right" 
			data-toggle="modal" 
			data-target="#rateaddress">
				Rate Location Data
	</button>
	@endif

@if(!$location->currentRating())
	
This location has not been rated yet.
@endif

}">

