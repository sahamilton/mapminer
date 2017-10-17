<script type="text/javascript" src="{{asset('assets/js/starrr.js')}}"></script>

<script>
	$(document).ready(function() 
    { 
	$(document).on('show.bs.modal','#confirm-delete', function(e) {
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
	});	

$('.starrr').on('starrr:change', function(e, value){
  
		    var id = e.target.id;
		    var returnVal = ranked(id,parseInt(value));

		  function ranked(id,value)
		       {
		         var myajax = $.ajax(
		      
		          {
		          
		          type: "GET",
		          
		          cache: false,
		       
		          
		          url: '{{route('api.leadrank')}}?api_token={{auth()->user()->api_token}}',
		          data: {id: id,value: value},
		          
		          dataType: "xml",
		          
		          contentType: "text/html",
		          
		         
		          
		          }); 
		          return myajax.responseText;
		          //end of $.ajax
		         
		        
		       }

		        function processData(){
		         //alert("I did it!");
		       }
		       
		       function errorAlert() {
		         alert("Whoops that didnt work");
		       }

		});
});
</script>