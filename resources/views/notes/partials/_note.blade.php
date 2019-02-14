<!-- Modal -->
<div class="modal fade" 
      id="add-note" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Record Location Note</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('notes.store')}}">
        @csrf
        <div class="form-group">
        <textarea name="note" class="form-control"></textarea>
          {{ $errors->first('note') }}
        </div>

          <div class="form-group float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 
          
           <input type="submit" value="Record Location Note" class="btn btn-danger" />
            </div>
            <input type="hidden" name = "address_id" value="{{$location->id}}" /> 
            <p><em>Do not use this form to record activities</em></p>
        </form>

        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>