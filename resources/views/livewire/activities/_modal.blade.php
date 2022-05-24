<div x-data="{ open: false }" class="flex justify-center">
    <!-- Trigger -->

    @php

    $data = [1,2,3,4];
    @endphp
    @foreach($data as $id)


    
        <a href="" x-on:click="open = true" class="">Address {{$id}}</a>
           
      @endforeach
 
<!-- Modal -->
     <div class="modal-dialog"
        x-show="open"
        style="display: none"
        x-on:keydown.escape.prevent.stop="open = false"
        role="dialog"
        aria-modal="true"
        x-id="['modal-title']"
        :aria-labelledby="$id('modal-title')"
        class="fixed inset-0 overflow-y-auto z-10"
    >
        <!-- Overlay -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

        <!-- Panel -->
        !-- Modal content-->
        <div class="modal-content"
            x-show="open" 
            x-transition
            x-on:click="open = false"
            class="relative min-h-screen flex items-center justify-center p-4"
        >
            <div
              x-on:click.stop
              x-trap.noscroll.inert="open"
              class="relative max-w-2xl w-full bg-white rounded-xl shadow-lg p-12 overflow-y-auto"
          >
              <!-- Title -->
              <div class="modal-content">
                <div class="modal-header">
                  
                  <h4 class="modal-title">Record Activity at {{$address ? $address->businessname : 'company'}}</h4>
                  <button type="button" class="close" wire:click.prevent="doClose()" >&times;</button>
                </div>
                <div class="modal-body">
                  <x-form>
                    <x-form-select 
                      required 
                      wire:model.defer="activitytype_id" 
                      name="activitytype_id" 
                      label="Activity:" 
                      :options="$activities" 
                      />
                    <x-form-textarea required wire:model.defer="note" name="note" label="Comments:" />
                    <x-form-input type="date" required wire:model.defer="activity_date" name="activity_date" label="Activity Date:" />
                    <x-form-checkbox checked wire:model.defer="completed" name="completed"  label="Completed:" />       
                    <x-form-input type="date" wire:model.defer="followup_date" name="followup_date" label="Followup Date:" />    
                    <x-form-select wire:model.defer="followup_activity" name="followup_activity" label="Followup Activity:" :options="$activityTypes" />
                    <input class="form-control" wire:model="address_id" id="address_id" name="address_id" value=""/>
                    
                
                    <div class="float-right">
                      <button class="btn btn-secondary"
                            type="button"
                            x-on:click="show=false">
                          Cancel
                        </button>
                      <button wire:click.defer="store" class="btn btn-danger">Record Activity</button>
                    
                    </div>

                  </x-form>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>