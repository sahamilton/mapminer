@extends('site/layouts/default')
@section('content')
<div class="page-header">
	<h1>Your Profile</h1>

</div>
<p><strong>First Name:</strong> {{$user->person->firstname}}</p>
<p><strong>Last Name:</strong> {{$user->person->lastname}}</p>
<p><strong>Phone:</strong> {{$user->person->phone}}</p>
<p><strong>Address:</strong>  {{$user->person->address}}</p>
<p><strong>User Name:</strong>  {{$user->username}}</p>
<p><strong>Latitude:</strong>  {{$user->person->lat}}</p>
<p><strong>Longitude:</strong>  {{$user->person->lng}}</p>
@if(count($user->person->industryfocus()->get()) > 0)
<p><strong>Industry Focus:</strong> 

@foreach ($user->person->industryfocus()->get() as $industry)
	<li>{{$industry->filter}}</li>
@endforeach
</p>
@endif
<p><strong>ServiceLines:</strong> 
@foreach($user->serviceline as $serviceline)

 {{$serviceline->ServiceLine }}
@if(! $loop->last) | @endif
@endforeach
</p>
<p><strong>Roles:</strong> 
@foreach($user->roles as $role)
 {{$role->name }}
 <?php $permissions[] = $role->permissions()->pluck('name')->toArray();?>
 @if(! $loop->last) | @endif
@endforeach
</p>

<p><strong>Permissions:</strong>
@foreach($permissions[0] as $key=>$name)
 {{$name}}
 @if(! $loop->last) | @endif
@endforeach
</p>


@if (isset($user->nonews))
<p><strong>No News before::</strong> 
{{$user->nonews->format("d M Y")}}</p>
<p>  Uncheck to reset:<input checked type='checkbox' id='nonews' name='noNews' /></p>
@endif
<a href="{{route('update.profile')}}">
<button type="button" class="btn btn-success" >
<i class="glyphicon glyphicon-pencil" ></i> Update</button></a>

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