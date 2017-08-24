<h2>Notes</h2>

@foreach ($project->relatedNotes as $note)
<p>{{date_format($note->created_at,'m-d-Y')}}...<em>{{$note->note}}</em><br />
 -- 
 @if(isset($note->writtenBy->person))
 {{$note->writtenBy->person->postName()}}
 @else
 No longer with company
 @endif
 </p>

@if($note->user_id == Auth::user()->id  or Auth::user()->hasRole('Admin'))
<br /><a href="{{route('notes.edit',$note->id)}}" title="Edit this note"><i class="glyphicon glyphicon-pencil"></i> </a> | 
<a data-href="{{route('notes.destroy',$note->id)}}" 
            data-toggle="modal" 
            data-target="#confirm-delete" 
            data-title = "note"  
            title="Delete this note"
            href="#">
            <i class="fa fa-trash-o" aria-hidden="true"> </i> </a>
           


<hr />
@endif


</p>

@endforeach
<?php $type="project";
$id=$project->id;?>
@include('notes.partials._form')
</div>