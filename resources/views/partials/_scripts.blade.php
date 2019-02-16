<script src="//twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
<script>
$(document).ready(function() 
    {
    
    $("#selectall").change(function(){
      $(".watchItem").prop('checked', $(this).prop('checked')).change();
     });

	$('[id^=checkAll]').change(function() {
	    var checkboxes = $(this).closest('form').find(':checkbox');
	    checkboxes.prop('checked', $(this).is(':checked'));
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
	$(document).on('show.bs.modal','#confirm-remove', function(e) {
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
	});	
	$(document).on('show.bs.modal','#add-activity', function(e) {
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
    	$(this).find('#address_id').html($(e.relatedTarget).data('id'));
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
	});	
	$(document).on('show.bs.modal','#accept-lead', function(e) {
    	$(this).find('.warning').attr('href', $(e.relatedTarget).data('href'));
		$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('input#lead_id').val($(e.relatedTarget).data('pk'));
	});
	$(document).on('show.bs.modal','#closeopportunity', function(e) {
    	
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
		
	});
	$(document).on('show.bs.modal','#reassign', function(e) {
    	
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
		
	});
	


	$(document).on('show.bs.modal','#add-activity', function(e) {
    	$(this).find('.warning').attr('href', $(e.relatedTarget).data('href'));
		$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('input#address_id').val($(e.relatedTarget).data('id'));
	});	
	$(document).on('show.bs.modal','#add-contact', function(e) {
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('input#company_id').val($(e.relatedTarget).data('pk'));
		
	});
	$(document).on('show.bs.modal','#unassign-lead', function(e) {
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('#action-form').attr('action',$(e.relatedTarget).data('href'));
	});	
	$(document).on('show.bs.modal','#add-locationcontact', function(e) {
    	$(this).find('#title').html($(e.relatedTarget).data('title'));
		$(this).find('input#address_id').val($(e.relatedTarget).data('pk'));
		
	});
	
	$('[id^=checkAll]').change(function() {
	    var checkboxes = $(this).closest('form').find(':checkbox');
	    checkboxes.prop('checked', $(this).is(':checked'));
	});

	$( "#activitydate" ).datepicker( {altField : "#activitydate",
    altFormat: "yyyy-mm-dd"});
    $( "#followupdate" ).datepicker( {altField : "#followupdate",
    altFormat: "yyyy-mm-dd"});
	$( "#todatepicker" ).datepicker( {altField : "#todate",
    altFormat: "yyyy-mm-dd"});
	
    $( "#fromdatepicker" ).datepicker({ altField : "#fromdate",
    altFormat: "yyyy-mm-dd"});
   
	$("[id^=sorttable]").DataTable();
	
	

	$("[id^=nosorttable]").DataTable(
		{

			"bPaginate": false,
		}
		);
	
	/*
	$('.starrr').on('starrr:change', function(e, value){
  
		  
		    var id = e.target.id;
			var type = e.target.className.replace("starrr ", '');
			
		    var returnVal = ranked(id,parseInt(value),type);
		    
		  	function ranked(id,value,type)
		       {
		         if (type && type=='lead') {
		         	var url = 'api.newlead.rank'?api_token={{auth()->user()->api_token}}';
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
//$("[id$=jander]")
	*/
        $('[id^=sorttable], #store-locator-container').on('change','.watchItem',function() {
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
)



// COPY TO CLIPBOARD
// Attempts to use .execCommand('copy') on a created text field
// Falls back to a selectable alert if not supported
// Attempts to display status in Bootstrap tooltip
// ------------------------------------------------------------------------------

function copyToClipboard(text, el) {
  var copyTest = document.queryCommandSupported('copy');
  var elOriginalText = el.attr('data-original-title');

  if (copyTest === true) {
    var copyTextArea = document.createElement("textarea");
    copyTextArea.value = text;
    document.body.appendChild(copyTextArea);
    copyTextArea.select();
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'Copied!' : 'Whoops, not copied!';
      el.attr('data-original-title', msg).tooltip('show');
    } catch (err) {
      console.log('Oops, unable to copy');
    }
    document.body.removeChild(copyTextArea);
    el.attr('data-original-title', elOriginalText);
  } else {
    // Fallback if browser doesn't support .execCommand('copy')
    window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
  }
}

$(document).ready(function() {
  // Initialize
  // ---------------------------------------------------------------------

  // Tooltips
  // Requires Bootstrap 3 for functionality
  $('.js-tooltip').tooltip();

  // Copy to clipboard
  // Grab any text in the attribute 'data-copy' and pass it to the 
  // copy function
  $('.js-copy').click(function() {
    var text = $(this).attr('data-copy');
    var el = $(this);
    copyToClipboard(text, el);
  });
});
</script>



