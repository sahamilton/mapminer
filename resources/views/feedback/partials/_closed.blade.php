<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		
		<th>Created</th>
		<th>Type</th>
		<th>Submitted By</th>
		<th>Feedback</th>
		<th>Posted From</th>
		<th>Status</th>
		<th>Biz Rating</th>
		<th>Tech Rating</th>
		<th>Comments</th>
		<th>Actions</th>

	</thead>
	<tbody>
	@foreach($feedback_closed as $item)



		<tr>  
		<td><a href="{{route('feedback.show',$item->id)}}">{{$item->created_at->format('M j, Y')}}</a></td>
		<td>{{$item->category->category}}</td>
		<td>{{$item->providedBy->person->fullName()}}
		<td>
			@if( strpos($item->feedback, '.')) 
				{{substr($item->feedback, 0, strpos($item->feedback, '.'))}} 
			@else 
				{{$item->feedback}} 
			@endif
		</td>
		<td>@if($item->url)<a href="{{$item->url}}" target="_blank" >{{$item->url}}</a>@endif</td>
		<td>{{$item->status}}</td>
		<td>{{$item->biz_rating}}</td>
		<td>{{$item->tech_rating}}</td>
		<td>{{$item->comments_count}}</td>
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">
				
				
					<a class="dropdown-item"
					href="{{route('feedback.edit',$item->id)}}" 
					title="Edit this Feedback">
					<i class="far fa-edit text-info"" aria-hidden="true"></i>
					Edit this feedback
				</a>
				@if($item->status =='open')
				<a class="dropdown-item"
					href="{{route('feedback.close',$item->id)}}" 
					title="Close this feedback">
					<i class="fas fa-check text-success"></i>
					Close this feedback
				</a>
				@else
				<a class="dropdown-item"
					href="{{route('feedback.open',$item->id)}}" 
					title="Close this feedback">
					<i class="fas fa-times text text-danger"></i>
					Reopen this feedback
				</a>

				@endif
					<a class="dropdown-item"
					 	data-href="{{route('feedback.destroy',$item->id)}}" 
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "feedback" 
						href="#">

						<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete Feedback</a>
				</ul>
			</div>	
		</td>
		</tr>
	@endforeach

	</tbody>
</table>