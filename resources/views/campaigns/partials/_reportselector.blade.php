 <div class="form-group{{ $errors->has('companies') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="companies">Companies</label>
        <div class="input-group input-group-lg ">
            <select class="form-control" 
              name="companies[]"
                  id="companies"
                  multiple>
                  @foreach ($campaign->companies as $company)
                  <option value="{{$company->id}}"
                      @if(isset($campaign) && in_array($company->id, $campaign->companies->pluck('id')->toArray()))
                          selected
                      @endif >
                      {{$company->companyname}}
                  </option>
                  @endforeach
          </select>
          </div>  
          <span class="help-block{{ $errors->has('vertical') ? ' has-error' : '' }}">
                <strong>{{$errors->has('companies') ? $errors->first('companies')  : ''}}</strong>
            </span>
         
     </div> 
<!-- Organization Alignment -->
OR
<legend>Organization</legend>
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