
@if(count($myBranches) > 1)
<div class="col form-inline"  title="Select branch"> 
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="fab fa-pagelines"></i></span>
    </div>
    <select wire:model="branch_id" 
        class="form-control input-sm" 
        id="branchselect" 
        name="branch_id">
        @foreach ($myBranches as $key=>$branchname)
            <option value="{{$key}}">{{$branchname}}</option>
        @endforeach 
    </select>
</div>
@endif  