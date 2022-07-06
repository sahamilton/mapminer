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

        <form wire:submit.prevent="closeOpportunity({{$opportunity->id}})">
          <div class="form-group-inline mb-4">

            @php $options = [1=>'Closed - Won', 2=>'Closed - Lost'];@endphp
            @foreach ($options as $key=>$value)
              <x-form-radio class="inline" name="opportunity.closed" wire:model="opportunity.closed" value="{{$key}}" label="{{$value}}"  />
            @endforeach

          </div>  
          <div class="form-group">
            <x-form-textarea required name="opportunity.comments" wire:model="opportunity.comments" label="Comments:" placeholder="Enter details...." />


          </div>
          <div class="form-group">
            <x-form-input type="date" name="opportunity.actual_close"
            wire:model="opportunity.actual_close"
            label="Actual Close:" />
          </div>

          <div class="float-right">
            <button type="submit" class="btn btn-danger">Close Opportunity</button>
          </div>
        </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
