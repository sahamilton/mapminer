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
 
$('#sorttable').on('change','.recipient',function() {
      var value = $(this).val();
      <!--var id = event.target.id;  Changed to accomodate Firefox -->
      var id =$(this).attr('id');
          if($(this).is(":checked")) {
			var action = 'add';
			var msg =  id + " to List";
			var returnVal = changed(msg,action,value);
			$(this).attr("checked", returnVal);
          }else{
			var action = 'remove';
			var msg = id + " from List";
			var returnVal = changed(msg,action,value);
			$(this).attr("checked",false, returnVal);
      }
         
       function changed(msg,action,id)
       {
         

         $.ajax(
      
          {
          headers: { 'csrftoken' : '{{ csrf_token() }}' },
          type: "GET",
          
          cache: false,
          
          url: '{{route('emails.updaterecipients')}}',
          data: {id: id,action: action, email_id: {{$email->id}}},
          
          dataType: "xml",
          
          contentType: "text/html",
          
          success: processData,
          
          error: errorAlert
          
          }); //end of $.ajax
      }
      function processData(){
         //alert("I did it!");
       }
       
       function errorAlert() {
         //alert("Whoops that didnt work");
       }
    });
</script>
