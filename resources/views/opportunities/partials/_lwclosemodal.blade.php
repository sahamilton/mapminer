  <div class="modal fade @if($closeOpportunityModal) show @endif"
         id="closeOpportunityModal"

         style="display: @if($closeOpportunityModal === true)
                 block
         @else
                 none
         @endif;"

         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-title"
         aria-hidden="true">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Close {{isset($opportunity) ? $opportunity->title :''}}  </h4>
        <button type="button" class="close"  wire:click.prevent="doClose('closeOpportunityModal')">&times;</button>
      </div>
      <div class="modal-body">

       
          <div class="form-group mb-4">
            
            @php $options = [1=>'Closed - Won', 2=>'Closed - Lost'];@endphp
            
            <select class="form-control"
              required
              name="opportunity.closed"
              wire:model="opportunity.closed">
              <option>Select</option>
              @foreach($options as $value=>$option)
              <option value={{$value}}>{{$option}}</option>
              @endforeach
            />
          </div>
             
          <div class="form-group">
            <label class="col-md-4 control-label">Comments</label>
            <textarea wire:mode ="opportunity.comments" 
            name="opportunity.comments" 
            required 
            class="form-control" 
            placeholder="Explain reason for closing"></textarea>
          </div>
          <div class="form-group">
            <x-form-input type="date" name="actual_close"
              wire:model="opportunity.actual_close"
              label="Actual Close" />
            </div>
            
            <div class="float-right">
            <button wire:click.ignore="closeOpportunity" class="btn btn-danger">Close Opportunity</button>
          </div>
        
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
