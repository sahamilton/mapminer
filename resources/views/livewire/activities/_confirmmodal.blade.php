
   @if(isset($activity))
    <div class="modal fade @if($confirmModal) show @endif"
         id="confirmModal"

         style="overflow-y:auto;display: @if($confirmModal === true)
                 block
         @else
                 none
         @endif;"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-title"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete activity from {{$activity->relatesToAddress->businessname}}</span> </h5>
                    
                    <button type="button" class="close"  wire:click.prevent="doClose('confirmModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete a {{$activity->completed ? ' completed ': ' pending' }} {{$activity->activitytype_id && $activity->activitytype_id != 'Select' ? $activityTypes[$activity->activitytype_id] : ''}} activity from {{$activity->relatesToAddress->businessname}}.</p>
                    <p class="text text-danger"> Are you sure?</p>
                    
                      <div class="float-right">
                        <button class="btn btn-secondary"
                              type="button"
                              wire:click="doClose('confirmModal')">
                            Cancel
                          </button>
                        <button wire:click.defer="confirmDelete('{{$activity->id}}')" class="btn btn-danger">Delete Activity</button>
                      
                      </div>
                   
                    
                </div>
                
            </div>
        </div>
    </div>
    @endif