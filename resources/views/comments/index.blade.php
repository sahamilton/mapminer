@extends('site/layouts/default')
@section('content')



<h2>Feedback</h2>
<div class="pull-right">
				<a href="{{{ Route('comment.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span>Add Feedback</a>
			</div>
@if (Auth::user()->hasRole('Admin'))
<a href="{{route('comment.download')}}">Download feedback to Excel</a>

@endif
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($key,$field)=each($fields))
    <th>
    {{$key}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($comments as $comment)
    <tr>  
	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
    <td><?php 
	
	switch ($key) {
		
		case 'Posted By':
		echo $comment->postedBy['username'];
		
		break;
		
		
		case 'Actions':
		
			?>
            @include('partials/_modal')
    
            <div class="btn-group">
			
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="/comment/{{$comment->id}}/edit/"><i class="glyphicon glyphicon-pencil"></i> Edit this comment</a></li>
				<li><a data-href="/comment/{{$comment->id}}/delete" data-toggle="modal" data-target="#confirm-delete" data-title = "this comment" href="#"><i class="glyphicon glyphicon-trash"></i> Delete this comment</a></li>
			  </ul>
			</div>
		
		<?php
		
		
		break;
		
		
		default:
			echo $comment->$field;
		break;
		
	};?>
	
    </td>
    @endwhile
    </tr>
   @endforeach
    
    </tbody>
    </table>
    </div>
@include('partials/_scripts')
@stop