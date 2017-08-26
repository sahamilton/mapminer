<h1>Project Notes</h1>

@foreach ($project->relatedNotes as $note)
	<p>{{$note->created_at->format('m-d-Y')}}...<em>
	@if($project->owned()  or auth()->user()->hasRole('Admin'))
		<a href="#" class="note"  id='{{$note->id}}' data-type="textarea" data-pk="1">
		{{$note->note}}</a>
	@else
		{{$note->note}}
	@endif
</em><br />
	 -- 
	 @if(null !==$note->writtenBy->person())
		 {{$note->writtenBy->person->postName()}}
	 @else
		 No longer with company
	 @endif
	 </p>
	@if($project->owned()  or auth()->user()->hasRole('Admin'))
		<br />
		<a href="{{route('notes.edit',$note->id)}}" title="Edit this note">
			<i class="glyphicon glyphicon-pencil"></i>
		</a> | 
		<a data-href="{{route('notes.destroy',$note->id)}}" 
		            data-toggle="modal" 
		            data-target="#confirm-delete" 
		            data-title = "note"  
		            title="Delete this note"
		            href="#">
		            <i class="fa fa-trash-o" aria-hidden="true"> </i> </a>
		           


		<hr />
	@endif
@endforeach
@if($project->owned())
	<form method='post' action={{route('notes.store')}} name="noteForm">
		{{csrf_field()}}
		<div>
			{{Form::label('note','Add a Note:')}}
			<div>
				{{Form::textarea('note')}}
				{{ $errors->first('note') }}
			</div>
		</div>
		<input type="hidden" name="type" value="project" />
		<input type='hidden' name='related_id' value="{{$project->id}}" />
		<button type="submit" class="btn btn-success">Add New Note</button>
	</form>

 @endif