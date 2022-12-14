<div class="col-md-8">
<h4>Project Notes</h4>

@if($project->relatedNotes->count()>0)
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
<thead>
<th>Date</th>
<th>Note</th>
<th>Author</th>
<th>Actions</th>
</thead>
<tbody>

@foreach ($project->relatedNotes as $note)
<tr>
	<td>{{$note->created_at->format('m-d-Y')}}</td>
	<td>{{$note->note}}</td>
	<td>
	 @if(null !==$note->writtenBy)
		 {{$note->writtenBy->person->fullName()}}
	 @else
		 No longer with company
	 @endif
	 </td>
	 <td>
	@if($project->owned()  or auth()->user()->hasRole('admin'))
		
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
	</tr>
	@endforeach

	</tbody>
	</tbody>
	</table>
@endif
@if($project->owner())
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
@else
<div class="alert alert-warning">Someone needs to own this project to add notes</div>

 @endif
 </div>
