<table>
	<tbody>
		<tr>
		
		<td>companyname</td>
		<td>businessname</td>
		<td>street</td>
		<td>city</td>
		<td>state</td>
		<td>zip</td>
		<td>contact</td>
		<td>phone</td>
		<td>Watched By</td>
		<td>Notes</td>
			
		</tr>

		@foreach($result as $location)
		<tr>
			<td>{{$location->company->companyname}}</td>
			<td>{{$location->businessname}}</td>
			<td>{{$location->street}} {{$location->suite}}</td>
			<td>{{$location->city}}</td>
			<td>{{$location->state}}</td>
			<td>{{$location->zip}}</td>
			<td>{{$location->contact}}</td>
			<td>{{$location->phone}}</td>
			<td>
				@foreach ($location->watchedBy as $watcher)
					{{$watcher->person->postName()}}
					@if(! $loop->last)<br />@endif
				@endforeach
			</td>
			<td>
				@foreach ($location->relatedNotes as $note)
					{{$note->note}}-
					@if(count($note->writtenBy)>0){{$note->writtenBy->person->postName()}} -@endif  {{$note->created_at->format('m-d-Y')}}
					@if(! $loop->last)<br />@endif
				@endforeach
			</td>
			</tr>
		@endforeach
	</tbody>
</table>