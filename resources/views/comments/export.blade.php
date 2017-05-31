<table>
	<tbody>
		<tr>
			<td>id</td>
			<td>created_at</td>
			<td>subject</td>
			<td>title</td>
			<td>comment</td>
			<td>comment_status</td>
			<td>user</td>

		</tr>
		@foreach($comments as $comment)
		
			<tr> 
			<td>{{$comment->id}}</td>
			<td>{{$comment->created_at}}</td>
			<td>{{$comment->subject}}</td>
			<td>{{$comment->title}}</td>
			<td>{{$comment->comment}}</td>
			<td>{{$comment->comment_status}}</td>
			<td>{{$comment->user}}</td> 
				
			</tr>
		@endforeach
	</tbody>
</table>