@extends('admin.layouts.default')


@section('content')
<style>
	
	#alert {
		display: none;
	}
	</style>
<h1>Create A News Item</h1>

<form name="createnews" method="post" action ="{{route('news.store')}}">
{{csrf_field()}}
@include('news.partials.newsform')
<input type="submit" name ="submit" class="btn btn-success" value="Create News Item" />
</div>

</form>
@include('partials._verticalsscript') 
@include('partials._scripts')


<script>

$('.summernote').summernote({
	  height: 300,                 // set editor height
	
	  minHeight: null,             // set minimum height of editor
	  maxHeight: null,             // set maximum height of editor
	
	  focus: true,                 // set focus to editable area after initializing summernote
	  toolbar: [
    //[groupname, [button list]]
     
    ['style', ['bold', 'italic', 'underline', 'clear']],
	['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['misc',['codeview']],
	
  ]
});
 

</script>

@endsection