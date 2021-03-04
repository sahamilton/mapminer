@if(count($myBranches)>1)
    <div class="col-sm-4">
        <select wire:model="branch_id" 
        class="form-control input-sm" 
        id="branchselect" 
        name="branch">
              @foreach ($myBranches as $key=>$branchname)
                    <option value="{{$key}}">{{$branchname}}</option>
              @endforeach 
        </select>
    </div>
@endif  