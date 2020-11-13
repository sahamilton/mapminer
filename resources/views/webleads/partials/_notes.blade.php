<h1>Lead Notes</h1>

@foreach ($lead->relatedNotes('weblead')->get() as $note)
	<p>{{$note->created_at->format('m-d-Y')}}...<em>{{$note->note}}</em><br />

	 @if(null !==$note->writtenBy && null!==$note->writtenBy->person())
		 {{$note->writtenBy->person->fullName()}}
	 @else
		 No longer with company
	 @endif
	 </p>
	@if(null !==$note->writtenBy && $note->writtenBy->id == auth()->user()->id  or auth()->user()->hasRole('admin'))
		<br />
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


		<hr />
	@endif


	</p>

@endforeach

	<form method='post' action={{route('notes.store')}} name="noteForm">
		{{csrf_field()}}
		<div class="form-group">
			<label for="note">Add a Note</label>
			
			<div class="form-item">
				<textarea name="note"></textarea>
				{{ $errors->first('note') }}
			</div>
		</div>
		<input type='hidden' name='related_id' value="{{$lead->id}}" />
		<input type="hidden" name="type" value="weblead" />
		<button type="submit" class="btn btn-success">Add New Note</button>
	</form>

