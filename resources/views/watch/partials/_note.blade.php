<div class="modal fade" id="noteform" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog" style = "width:400px">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <h3>Add Note</h3>
                <form id ='addNoteForm' >
                <div class="form-group @if ($errors->has('news')) has-error @endif">
                {{Form::textarea('note')}}
                </div>
                <input type="hidden" id="location_id" name="location_id" value="" />
                
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type='button' id='addNote' type="button" value='Save' class="btn btn-primary" data-dismiss="modal" >Save</button>
                
        </div>
    </div>
  </div>
</div>
<script>
$(function () {
   
	
	$("#addNote").click(function(){
			var note = $('#addNoteForm :input').serialize();
			
			$.get("{{route('addNewNote')}}",note,function(response,status){
				
				window.location.reload(true);});
			
				
			});	
	});	


	</script>


