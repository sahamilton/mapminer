@extends('site/layouts/default')
@section('content')
<?php $roles ='';?>


{{-- Content --}}
@section('content')
<div class="page-header">
	<h1>Your Profile</h1>
</div>


@foreach($fields as $key=>$field)

<p><strong>{{$key}}:</strong>
@if($value == 'username')
 {{$user->$value}}</p>
 
	
@elseif ($key == 'Roles')
@foreach($user->roles as $role)
    
    <?php $roles.=$role->name ." | ";?>
   
    @endforeach
    <?php $roles = substr($roles,0,-2);?>
    {{$roles}} </p>
 @else
{{$user->person->$value}}</p>
@endif



@endforeach

@if (isset($user->nonews))
<p><strong>No News before:</strong>{{date("d M Y",strtotime($user->nonews))}}</p><p>  Uncheck to reset:<input checked type='checkbox' id='nonews' name='noNews' /></p>
@endif
<a href="/user"><button type="button" class="btn btn-success" ><i class="glyphicon glyphicon-pencil" ></i> Update</button></a>




<script>
$(document).ready(function() {
			 $("#nonews").change(function(){
				 if (this.checked) {
						$.get( '/api/news/nonews', function(response){
				 /* ajax is complete here, can do something with response if needed*/
			 		})
						
				}else{
					$.get( '/api/news/setnews', function(response){
				 		/* ajax is complete here, can do something with response if needed*/
			 		})
					
				};
			
		
		});
});
</script>
@stop