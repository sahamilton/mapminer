<!-- Modal -->
<div class="modal fade" 
  id="add_lead" 
  tabindex="-1" 
  role="dialog" 
  aria-labelledby="myModalLabel" 
  aria-hidden="true">
  <div class="modal-dialog">

  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Lead</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="col-md-6">
          <form id="address-form" action="" method="get" autocomplete="off">      
            <x-form-input required name="ship-address" id="ship-address" label="Address" />
            <x-form-input name="address2" id="address2" label="Apartment, unit, suite, or floor #" />
            <x-form-input required name="locality" id="locality" label="City*" />
            <x-form-input required name="state" id="state" label="State/Province*" />
            <x-form-input required name="postcode" id="postcode" label="ZIP / Postal Code*" />   
            <x-form-input required name="country" id="country" label="ZIP / Postal Code*" /> 
            <button type="button" class="my-button">Save address</button>


          </form>
        </div>
      </div>
      <div class="modal-footer">


      </div>
    </div>
  </div>
   @include('partials.scripts._autofill')
</div>