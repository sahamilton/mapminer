@extends('admin/layouts/default')
<?php $groups = Howtofield::select('group')->get();
foreach($groups as $group) {
	
	$groupsSelect[$group->group] = str_replace("_"," ",$group->group);
}
?>

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3> Edit </h3>
		

		<div class="pull-right">
			<a href="{{ route('admin.howtofields.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	
</div>


<?php $buttonLabel = 'Edit Field';
?>

{{Form::model($howtofield,['method'=>'PATCH','route'=>['admin.howtofields.update', $howtofield->id]])}}
	@include('howtofields/partials/_form')
{{Form::close()}}

    
<script>

$('#add').click(function() {
	var addOption = $('#addGroup').val();
	$('#group').append('<option value="' + addOption + '">' + addOption + '</option>');
	//alert("You added " + addOption);
	
	
});
</script>@endsection