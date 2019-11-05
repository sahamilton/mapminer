<div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="organization">Organization</label>
        <div class="input-group input-group-lg ">
            
            <select name="manager_id" id="manager" class="form-control input-lg">
                <option value="">All Managers</option>
               @foreach($team as $manager) 
                <option 
                @if (isset($campaign) && $campaign->manager_id == $manager->id) selected @endif
                value="{{$manager->id}}">
                    {{$manager->fullName()}} 
                        (<em>
                            {{$manager->userdetails->roles->first()->display_name}})
                        </em>
                </option>
                @endforeach
            </select>
            <span class="help-block{{ $errors->has('organization') ? ' has-error' : '' }}">
                <strong>{{$errors->has('organization') ? $errors->first('organization')  : ''}}</strong>
            </span>
         </div>
     </div> 