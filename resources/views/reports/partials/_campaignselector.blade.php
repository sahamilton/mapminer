<div class="form-group{{ $errors->has('campaign') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="campaign">Active Campaigns</label>
        <div class="input-group input-group-lg ">
            
            <select 
            name="campaign_id" 
            id="campaign" 
            class="form-control input-lg"
            >
                
               @foreach($campaigns as $cmp) 
                <option 

                @if (isset($campaign) && $campaign->id == $cmp->id) selected @endif
                value="{{$cmp->id}}">
                    {{$cmp->title}} 
                        
                </option>
                @endforeach
            </select>
            <span class="help-block{{ $errors->has('campaign') ? ' has-error' : '' }}">
                <strong>{{$errors->has('campaign') ? $errors->first('campaign')  : ''}}</strong>
            </span>
         </div>
     </div> 