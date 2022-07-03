<!-- Modal -->
<div class="modal fade  @if($transferModal) show @endif"
     id="transferModal"

     style="display: @if($transferModal === true)
             block
     @else
             none
     @endif;"
  role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Transfer {!! $address->businessname !!} :-)  </h4>
        <button wire:click="doClose('transferModal')">&times;</button>
      </div>
      <div class="modal-body">

          @wire()
          
            <x-form-select required name="transferbranch" :options="$branches" label="Transfer to branch:" />
            <div class="float-right">
              <button class="btn btn-secondary"
                type="button"
                wire:click="doClose('transferModal')">
                Cancel
              </button>
              <button wire:click="transferLead('{{$address->id}}')" class="btn btn-danger">Transfer</button>

            </div>
          @endwire
      </div>
      <div class="modal-footer">
     </div>
    </div>
  </div>
</div>
