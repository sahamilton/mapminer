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
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
	});	
	$(document).on('show.bs.modal','#accept-lead', function(e) {
    	$(this).find('.warning').attr('href', $(e.relatedTarget).data('href'));
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
			var type = e.target.className.replace("starrr ", '');
			
		    var returnVal = ranked(id,parseInt(value),type);
		    
		  	function ranked(id,value,type)
		       {
		         if (type && type=='lead') {
		         	var url = '{{route('api.lead.rank')}}?api_token={{auth()->user()->api_token}}';
		         }else{
		         	var url = '{{route('api.rank')}}?api_token={{auth()->user()->api_token}}';
		         }
		         var myajax = $.ajax(
		      
		          {
		          
		          type: "GET",
		          
		          cache: false,
		       
		          
		          url: url,
		          data: {id: id,value: value,type: type},
		          
		          dataType: "json",
		          
		          contentType: "json",
		          
		         
		          
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
		var id = $(this).val();
        if($(this).is(":checked")) {
			var action = 'add';
			var msg =  id + " to List";
            var returnVal = changed(msg,action,id);
            $(this).attr("checked", returnVal);
            
        }else{
			var action = 'remove';
			var msg = id + " from List";
			var returnVal = changed(msg,action,id);
            $(this).attr("checked",false, returnVal);
		}
       
	   function changed(msg,action,id)
	   {
		   $.ajax(
		
				{
				
				type: "GET",
				
				cache: false,
				
				url: '{{route("api.watchupdate")}}',

				data: {id: id,action: action,api_token:"{{auth()->user()->api_token}}"},
				
				dataType: "xml",
				
				contentType: "json",
				
				success: processData
				
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