<div class="col mb8">
    <div class="input-group-prepend">
    <span class="input-group-text"><i class="fas fa-search"></i></span>

        <input wire:model.debounce.150ms="search" class="form-control" type="text" placeholder="{{isset($placeholder) ? $placeholder : 'Search list'}}">
    </div>
</div>