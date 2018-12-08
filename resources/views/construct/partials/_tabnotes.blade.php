<h1>Project Notes</h1>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
<thead>
<th>Date</th>
<th>Note</th>
<th>Author</th>
<th>Actions</th>
</thead>
<tbody>

@foreach ($project->relatedNotes as $note)
	<td>{{$note->created_at->format('m-d-Y')}}</td>
	<td>@if($project->owned()  or auth()->user()->hasRole('Admin'))
		<a href="#" class="note"  id='{{$note->id}}' data-type="textarea" data-pk="1">
		{{$note->note}}</a>
	@else
		{{$note->note}}
	@endif
</td>
	 <td>
	 @if(null !==$note->writtenBy->person())
		 {{$note->writtenBy->person->postName()}}
	 @else
		 No longer with company
	 @endif
	 </td>
	 <td>
	@if($project->owned()  or auth()->user()->hasRole('Admin'))
		
		<a href="{{route('notes.edit',$note->id)}}" title="Edit this note">

			<i class="far fa-edit text-info"" aria-hidden="true"></i>

		</a> | 
		<a data-href="{{route('notes.destroy',$note->id)}}" 
		            data-toggle="modal" 
		            data-target="#confirm-delete" 
		            data-title = "note"  
		            title="Delete this note"
		            href="#">

		            <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> </a>
	
	@endif
	</td>
	</tbody>
	</table>
@endforeach
@if($project->owned())
	<form method='post' action={{route('notes.store')}} name="noteForm">
		{{csrf_field()}}
		<div>
			<label for ='note'>Add a Note:</label>
			<div>
				<textarea name='note'></textarea>
				{{ $errors->first('note') }}
			</div>
		</div>
		<input type="hidden" name="type" value="project" />
		<input type='hidden' name='related_id' value="{{$project->id}}" />
		<button type="submit" class="btn btn-success">Add New Note</button>
	</form>

 @endif
 <script>
 $(document).ready(function() {
    $('#{{$note->id}}').editable();
});</script>
