@extends('admin/layouts/default')
@section('content')
<?php $group = NULL;?>
<h3>Edit Sales Notes for {{$company->companyname}}</h3>
@if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
        @endif
<div class ='pull-right'>
<a href="{{route('admin.howtofields.index')}}">Edit sales note fields</a>
</div>
<div id='tabs'>

<ul>
<?php 
$groups = Howtofield::select('group')->distinct()->get();

foreach ($groups as $tab) {
	if(!isset($n)) {
		$group = $tab;
	$n=TRUE;	
	}
	echo "<li>";
   		echo "<a href=\"#" . str_replace(" ","_", $tab['group'])."\">".$tab['group']."</a>";
    echo "</li>";
}?>

</ul>

<div id="{{$group}}">
{{Form::open(array('route'=>'admin.salesnotes.store','files'=>true))}}
@include('salesnotes.partials._form')
 {{Form::hidden('companyId',$company->id)}}
{{Form::close()}}
</div>
 <script>

$(function() {
  $("#tabs").tabs();
 
});
  </script>


@stop