@php $myBranches = auth()->user()->person->myBranches(); @endphp
@if(count($myBranches)>1)
    <div class="col-sm-4">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fab fa-pagelines"></i></span>
        </div>
        <select wire:model="branch_id" 
        class="form-control input-sm" 
        id="branchselect" 
        name="branch">
            <option value='All'>All Branches</option>
              @foreach ($myBranches as $key=>$branchname)
                    <option value="{{$key}}">{{$branchname}}</option>
              @endforeach 
        </select>
    </div>
@endif  