<div class="modal fade @if($activityModalShow) show @endif"
     id="activityModalShow"

     style="overflow-y: auto;display: @if($activityModalShow === true)
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
                <h5 class="modal-title">{{isset($title) ? $title : 'Record'}} Activity at <span id="title"> {{isset($address) ? $address->businessname :'company'}} </span></h5>
                
                <button type="button" class="close"  wire:click.prevent="doClose('activityModalShow')">&times;</button>
            </div>
            <div class="modal-body">
                @wire()
                    @if($errors->any())
                        <span class="text-danger">Check your inputs</span>
                    @endif
                    <div class="form-group " >
                       
                        <label for="activity.activitytype_id">Activity Type:</label>
                        <select wire:model="activity.activitytype_id"

                            class="form-control  {{ $errors->has('activity.activitytype_id') ? ' has-error is-invalid' : '' }} ">
                            <option>Select</option>
                            @foreach ($activityTypes as $key=>$type)
                                <option value="{{$key}}">{{$type}}</option>
                            @endforeach
                        </select>
                        @error('activity.activitytype_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    @if(isset($activity->relatesToAddress) && $activity->relatesToAddress->contacts->count() > 0)
                        @php 
                            $contacts[] ='Select..';
                            $contacts = $contacts + $activity->relatesToAddress->contacts->pluck('fullname', 'id')->toArray(); 
                            
                        @endphp
              
                        <x-form-select  name="contact_id" label="Contact:" :options="$contacts" />
                    @endif
                    <x-form-textarea required name="activity.note" label="Comments:" placeholder="Enter details...." />
                    <x-form-input required type="date"  id="activity_date" name="activity.activity_date" label="Activity Date:" />
                    <x-form-checkbox checked  name="activity.completed"  value='1' label="Completed:" />       
                    <x-form-input type="date" name="activity.followup_date" label="Followup Date:"  />
                    @if(isset($activity) && $activity->followup_date)   
                        <div class="form-group">
                            <label for="followupactivitytype">Followup Activity Type:</label>
                            <select wire:model="followupactivitytype" 
                                class="form-control {{ $errors->has('followupactivitytype') ? ' has error is-invalid' : '' }}">
                                <option>Select</option>
                                @foreach ($activityTypes as $key=>$type)
                                    <option value="{{$key}}">{{$type}}</option>
                                @endforeach
                            </select>
                            @error('followupactivitytype') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    

                    <div class="float-right">
                        <button class="btn btn-secondary"
                            type="button"
                            wire:click.prevent="doClose('activityModalShow')">
                            Cancel
                        </button>
                        <button wire:click.ignore="{{isset($method) ? $method : 'updateActivity'}}('{{isset($activity) ? $activity->id : ''}}')" class="btn btn-danger">{{isset($title) ? $title : 'Record'}} Activity</button>

                    </div>
                @endwire
            </div>
        </div>
    </div>
</div>