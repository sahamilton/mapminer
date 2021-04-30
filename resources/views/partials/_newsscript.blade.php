<script type="text/javascript">
    $(window).load(function(){
        $('#flashNews').modal('show');
    });

	 $("#nonews").change(function(){
		 if (this.checked) {
				$.get( '/api/news/nonews', function(response){
		 /* ajax is complete here, can do something with response if needed*/
	 		})
				
		}else{
			$.get( '/api/news/setnews', function(response){
		 		/* ajax is complete here, can do something with response if needed*/
	 		})
			
		}
	});
			
</script>