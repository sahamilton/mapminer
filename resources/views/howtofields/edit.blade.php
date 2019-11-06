@extends('admin/layouts/default')


{{-- Page content --}}
@section('content')
<div class="page-header">
    <h3> Edit </h3>
        

        <div class="float-right">
            <a href="{{ route('howtofields.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
        </div>
    
</div>



<form method="post" 
action = "{{route('howtofields.update',$howtofield->id)}}" >
    @csrf
    @method('patch')

    @include('howtofields/partials/_form')
    <input type="submit"
        name="submit"
        value="Update Field" />
</form>

    
<script>

$('#add').click(function() {
    var addOption = $('#addGroup').val();
    $('#group').append('<option value="' + addOption + '">' + addOption + '</option>');
    //alert("You added " + addOption);
    
});

</script>
@endsection
