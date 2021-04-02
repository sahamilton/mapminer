@if(count($companies) >1 )
    <div class="col-sm-4">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fab fa-pagelines"></i></span>
        </div>
        <select wire:model="company_id" 
        class="form-control input-sm" 
        id="branchselect" 
        name="branch">
            
              @foreach ($companies as $id=>$companyname)
                    <option value="{{$id}}">{{$companyname}}</option>
              @endforeach 
        </select>
    </div>
@endif  