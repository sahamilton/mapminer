@extends('admin/layouts/default')
<?php $groups = Howtofield::select('group')->distinct()->get();
$groupsSelect=array();
foreach($groups as $group) {
	
	$groupsSelect[$group->group] = str_replace("_"," ",$group->group);
}
?>

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3> Create New How To Field </h3>
		

		<div class="pull-right">
			<a href="{{ route('admin.howtofields.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	
</div>


<?php $buttonLabel = 'Create New Field';?>
{{Form::open(['route'=>'admin.howtofields.store'])}}
	@include('howtofields/partials/_form')
{{Form::close()}}

    
<script>

$('#add').click(function() {
	var addOption = $('#addGroup').val();
	$('#group').append('<option value="' + addOption + '">' + addOption + '</option>');
	//alert("You added " + addOption);
	
	
});
</script>

@endsection
