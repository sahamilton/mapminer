
<div id="footer" >
	<div class="container">
		<div class="muted credit col-sm-6 " style="color:#000000">
		&copy;2014 - <?php echo date("Y");?>  <a href="http://www.elaconsultinggroup.com"
		title="Visit the ELA COnsulting Group website" target="_blank">ELA Consulting Group, LLC </a>/ TrueBlue, Inc.
		</div>
		 @if(config('app.env')=='local' or config('app.env')=='staging')
			<div class="pull-right col-sm-4" style="color:black">
				{{App::environment()}} | {{App::version()}}| {{config('mapminer.app_version')}} | {{ phpversion() }}
			</div>
		@endif
	</div>
</div>