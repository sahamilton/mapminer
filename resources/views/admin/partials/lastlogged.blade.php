<div class="chart-containter"
style = "width:400;float:left;border:1px solid #000;margin:20px;padding:20px;"> 
<h4>Last Logged In</h4>
<canvas id="pieChart" width="250" height="200"></canvas>
<table class="table"><thead>
	<tr>
		<th>Color</th>
		<th>Period</th>
		<th>Count</th>
		<th>Total</th>
	</tr>
</thead>
<tbody>
	<?php $n=0;
	$cum = 0;?>
	@foreach ($data['status'] as $status)
		<tr>
			<td>
				<span style="background-color:{{$color[$n]}};border:1px solid black">&nbsp;&nbsp;&nbsp;&nbsp;</span>
			</td>
			<td>
				<a href="{{route('admin.showlogins',substr($status->status,0,1) - 1)}}" 
					title="List {{trim(strtolower(substr($status->status,3)))}}'s users">{{$status->status}}
				</a> 
			</td>
			<td style="text-align:right">{{$status->count}}</td>
				@php
					$cum = $cum + $status->count;
					$n++;
				@endphp
			<td style="text-align:right">{{$cum}}</td>
		</tr>
	@endforeach
</tbody>
</table>
</div>