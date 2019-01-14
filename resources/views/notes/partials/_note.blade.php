<style>

.activity_date, .followup_date{z-index:1151 !important;}
</style>

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
        <textarea name="note"></textarea>
          {{ $errors->first('note') }}

          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 
           <input type="submit" value="Record Activity" class="btn btn-danger" />
            </div>
            <input type="hidden" name = "address_id" value="{{$location->id}}" />
        </form>

        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>