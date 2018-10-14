<footer id = "footer" class="bg-dark text-white mt-4" >
	<div class="container-fluid py-2">
		<div class="row">	
			<div class="col-sm-8">
				&copy;2014 - <?php echo date("Y");?>  
				<a href="//www.elaconsultinggroup.com"
				title="Visit the ELA Consulting Group website" 
				target="_blank"> ELA Consulting Group, LLC </a>/ TrueBlue, Inc.
			</div>
			@if(config('app.env')=='local' or config('app.env')=='staging')
				<div class="float-right" style="color:grey">
					{{App::environment()}} | {{App::version()}}| {{config('mapminer.app_version')}} | {{ phpversion() }}
				</div>
			@endif
		</div>
	</div>
</footer>