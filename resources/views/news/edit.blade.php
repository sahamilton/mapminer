@extends('site/layouts/default')


@section('content')
<style>
	
	#alert {
		display: none;
	}
	</style>
<h1>Edit Update</h1>



<form name="editnews" method="post" action = "{{route('news.update',$news->id)}}" >
{{csrf_field()}}
<input type="hidden" name="_method" value="patch" />
@include('news.partials.newsform')
<input type="submit" name ="submit" class="btn btn-success" value="Edit News Item" />
<input type="hidden" name="id" value={{$news->id}} />
</form>


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

@stop