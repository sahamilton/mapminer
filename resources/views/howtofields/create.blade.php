@extends('admin/layouts/default')
<?php $groupsSelect=array();

foreach($groups as $group) {
    
    $groupsSelect[$group->group] = str_replace("_", " ", $group->group);
}
?>

{{-- Page content --}}
@section('content')
<div class="page-header">
    <h3> Create New How To Field </h3>
        

        <div class="float-right">
            <a href="{{ route('howtofields.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
        </div>
    
</div>

<form
name="howtofieldscreate"
method="post"
action = "{{route('howtofields.store')}}">
    @csrf
    @include('howtofields.partials._form')
    <input type= "submit"
        name="submit"
        value="Create New Field" />

</form>
    
<script>

$('#add').click(function() {
    var addOption = $('#addGroup').val();
    $('#group').append('<option value="' + addOption + '">' + addOption + '</option>');
    //alert("You added " + addOption);
    
    
});
</script>

@endsection
