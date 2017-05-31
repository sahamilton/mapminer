<script>
$(document).ready(function() 
    { 
      
    
    $("#selectall").change(function(){
      $(".watchItem").prop('checked', $(this).prop('checked')).change();
     });

    $(".more").toggle(function(){
    $(this).text("less..").siblings(".complete").show();    
	}, function(){
	    $(this).text("more..").siblings(".complete").hide();    
	});
    $("button.disabled").click(function(){
    	event.preventDefault();
	    $("#message").toggle();
	});
	$(document).on('show.bs.modal','#confirm-delete', function(e) {
    	$(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
		$(this).find('#title').html($(e.relatedTarget).data('title'));
	});	
	
	$( "#todatepicker" ).datepicker( {altField : "#todate",
    altFormat: "yyyy-mm-dd"});
	
    $( "#fromdatepicker" ).datepicker({ altField : "#fromdate",
    altFormat: "yyyy-mm-dd"});
	$('#sorttable').DataTable();
	
	$('#sorttable1').DataTable();
	
	$('#sorttable2').DataTable();
	
	$('#sorttable3').DataTable();
	
	$('#sorttable4').DataTable();
	
	$('#sorttable5').DataTable();
		
	$('#sorttable6').DataTable();

	$('#sorttablenosort').DataTable(
		{
			"bSort":false,
			"bPaginate": false,
		}
		);
	
	$('.starrr').on('starrr:change', function(e, value){
  
		    var id = e.target.id;
		    var returnVal = ranked(id,parseInt(value));

		  function ranked(id,value)
		       {
		         var myajax = $.ajax(
		      
		          {
		          
		          type: "GET",
		          
		          cache: false,
		       
		          
		          url: '{{route('api.rank')}}?api_token={{\Auth::user()->api_token}}',
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

	
    $('#sorttable, #store-locator-container').on('change','.watchItem',function() {
		var value = $(this).val();
		<!--var id = event.target.id;  Changed to accomodate Firefox -->
		var id =$(this).attr('id');
        if($(this).is(":checked")) {
			var action = 'add';
			var msg =  id + " to List";
            var returnVal = changed(msg,action,id);
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
				
				type: "GET",
				
				cache: false,
				
				url: '{{route('api.watchupdate')}}',
				data: {id: id,action: action},
				
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
		   alert("Whoops that didnt work");
	   }
    });
    } 
); 
</script>