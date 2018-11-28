
	<h2>Campaign Description</h2>
	<p>{{$activity->description}}</p>


		<h4>Verticals:</h4>
		<?php $verticals = array();?>
		<uL>
		@foreach ($activity->vertical as $vertical)
			@if(! in_array($vertical->filter,$verticals))
			<li> {{$vertical->filter}}</li>
			<?php $verticals[]=$vertical->filter;?>
			@endif
		@endforeach
	<</uL>
	<ul>
		<h4>Sales Process:</h4>

		<?php $processes = array();?>
		@foreach ($activity->salesprocess as $process)
			@if(! in_array($process->step,$processes))
			<li> {{$process->step}}</li>
			<?php $processes[] = $process->step;?>
			@endif
		@endforeach
</ul>
