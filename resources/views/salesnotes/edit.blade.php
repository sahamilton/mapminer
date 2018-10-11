@extends('admin.layouts.default')
@section('content')
<?php $group = NULL;?>
<h3>Edit Sales Notes for {{$company->companyname}}</h3>

<div class ='pull-right'>
<a href="{{route('howtofields.index')}}">Edit sales note fields</a>
</div>
<div id='tabs'>

<ul>

@foreach ($groups as $tab) 
	@if(!isset($n)) 
		<?php $group = $tab;
	$n=TRUE;?>	
	@endif
	<li>
   		<a href="#{{str_replace(" ","_", $tab['group'])}}">{{$tab['group']}}</a>
   </li>
@endforeach

</ul>

<div id="{{$group}}">
{{Form::open(array('route'=>'salesnotes.store','files'=>true))}}
@include('salesnotes.partials._form')
 {{Form::hidden('companyId',$company->id)}}
 </div><div style="margin-top:20px">
    <div class="controls">

      <button type="submit" class="btn btn-success">Edit Notes</button>
    </div>
  </div>
{{Form::close()}}
</div>
 <script>

$(function() {
  $("#tabs").tabs();
 
});
  </script>


<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
