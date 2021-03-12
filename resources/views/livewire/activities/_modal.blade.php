<style>

.activity_date, .followup_date{z-index:1151 !important;}
</style>

<!-- Modal -->
<div  wire:ignore.self  
      class="modal fade" 
      id="add-lwactivity" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Record Activity at <span id="title">Company</span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
       
        @include('livewire.activities._activityform')
          <div class="float-right">
           <button 
           type="button" 
           class="btn btn-default close-modal" 
           data-dismiss="modal">Cancel</button> 
           
           <button wire:click.prevent="store()"
           
           class="btn btn-danger" />Record Activity</div>
            </div>


        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>