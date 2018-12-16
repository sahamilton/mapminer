<h1>Location Watched By</h1>
<div class="col-md-8 col-md-offset-2">

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Watched By</th>
    <th>Since</th>
 
  
       
    </thead>
    <tbody>
		@foreach ($location->location->watchedBy as $watcher)
			<tr>
				<td>
					
					{{$watcher->person()->first()->firstname}} 
					{{$watcher->person()->first()->lastname}}
					
				</td>
				<td>{{$watcher->pivot->created_at->format('M d, Y')}}</td>

		
			</tr>
		@endforeach
	</tbody>
</table>
</div>

