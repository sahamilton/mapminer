@extends('site/layouts/default')
@section('content')
<div class="page-header">
	<h1>Your Profile</h1>

</div>
<p><strong>First Name:</strong> {{$user->person->firstname}}</p>
<p><strong>Last Name:</strong> {{$user->person->lastname}}</p>
<p><strong>Phone:</strong> {{$user->person->phone}}</p>

@if($user->person->lat)
 <div> @include('admin.users.partials._personmap')</div>
 <div style="clear:both"> 
 	<p><strong>Latitude:</strong>  {{$user->person->lat}} <strong>Longitude:</strong>  {{$user->person->lng}}</p>

@endif
<p><strong>User Name:</strong>  {{$user->username}}</p>
<p><strong>Email:</strong>  {{$user->email}}</p>

@if($user->person->industryfocus()->get())
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
{{$user->nonews->format("d M Y")}}
 Uncheck to reset:<input checked type='checkbox' id='nonews' name='noNews' /></p>
@endif
<a href="{{route('user.edit',$user->id)}}">
<button type="button" class="btn btn-success" >
<i class="fa fa-pencil" aria-hidden="true"></i> Update</button></a>

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