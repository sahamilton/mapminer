<div class="chart-containter" style = "width:300;float:left;border:1px solid #000;margin:20px;padding:20px;"> 
   <h4>Last Logged In</h4>
<canvas id="pieChart" width="250" height="200"></canvas>
<table class="table"><thead>
    <tr><th>Color</th>
    <th>Period</th>
    <th>Count</th>
    <th>Total</th></tr>
    </thead>
<tbody>
<?php $n=0;
$cum = 0;
foreach ($data['status'] as $status){
	echo "<tr><td><span style=\"background-color:".$color[$n]."\">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
	echo "<td><a href=\"/admin/userlogin/".(substr($status['status'],0,1) - 1) ."\" title=\"list these users\">".$status['status']."</a> </td>";
	echo "<td style=\"text-align:right\">" . $status['count']."</td>";
	$cum = $cum + $status['count'];
	echo "<td style=\"text-align:right\">" . $cum."</td></tr>";
	$n++;
	
}?>
</tbody>
</table>
</div>