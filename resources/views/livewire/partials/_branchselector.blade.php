@if(count($myBranches) > 1)
    <div class="col form-inline">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fab fa-pagelines"></i></span>

            <x-form-select class="col form-inline" wire:model="branch_id" name="branch_id" :options="$myBranches" />
        </div>
     </div>   

@endif  