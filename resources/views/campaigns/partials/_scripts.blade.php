<script type="text/javascript" src="{{asset('/assets/js/bootstrap-datepicker.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker3.css')}}"/>
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
 
$('#datepicker .input-group.date').datepicker({
});

$('#datepicker1 .input-group.date').datepicker({
});

</script>
