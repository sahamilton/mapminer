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
@include('campaigns.partials._teamselector')