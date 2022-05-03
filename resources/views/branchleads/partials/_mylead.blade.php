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
        
          <form id="address-form" action="{{route('myleads.store')}}" method="post" autocomplete="off">  
            @csrf
            <x-form-input required name="companyname"  id="companyname" label="Company*" />
            <x-form-checkbox name="isCustomer" label="Is Customer?" />
            <x-form-input required name="address"  id="ship-address" label="Address*" />
            <x-form-input name="address2"  id="address2" label="Apartment, unit, suite, or floor #" />
            <x-form-input required name="city"  id="city" label="City*" />
            <x-form-input required name="state"  id="state" label="State/Province*" />
            <x-form-input required name="postcode"  id="postcode" label="ZIP / Postal Code*" />   
            <x-form-input required name="country"  id="country" label="Country" value="{{auth()->user()->person->country}}"/> 

            @php
            $branches = auth()->user()->person->getMyBranches();

            @endphp
            @if (count($branches) > 1)
            <div class="form-group row">
                <label for="branch" 
                    class="col-md-2 control-label">Branch: </label>
                 <div class="col-sm-8">
                    <select
                        
                        name="branch"
                        required
                        >
                        @foreach ($branches as $title)
                            <option value={{$title}}>{{$title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @else
            <input type="hidden" name="branch" value="{{reset($branches)}}" />
            @endif
            <input type="submit" class="btn btn-success" value="Save lead" />


          </form>
        </div>
     
      <div class="modal-footer">


      </div>
    </div>
  </div>

</div>