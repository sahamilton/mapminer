<footer id = "footer" class="bg-dark text-white mt-4" >
	<div class="container-fluid py-2">
		<div class="row">	
			<div class="col-sm-8">
				&copy;2014 - <?php echo date("Y");?>  
				<a href="{{config('mapminer.website')}}"
				title="Visit the {{config('mapminer.developer')}} website" 
				target="_blank"> {{config('mapminer.developer')}} </a>/ {{config('mapminer.client')}}
			</div>
			@if(config('app.env')=='local' or config('app.env')=='staging')
				<div class="float-right" style="color:grey">
					{{ucwords(App::environment())}} | 
					v{{config('mapminer.app_version')}} |
					 {{config('mapminer.app_version')}} |
					{{ ucwords(exec('git rev-parse --abbrev-ref HEAD'))}} |
					 {{ phpversion() }}
				</div>
			@endif
		</div>
	</div>
</footer>
