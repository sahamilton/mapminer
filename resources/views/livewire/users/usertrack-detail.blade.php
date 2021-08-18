<div>
    <h2>{{$manager->fullName()}} Mapminer Stats</h2>
    <h3>For the period from {{session('period')['from']->format('Y-m-d')}} to {{session('period')['to']->format('Y-m-d')}}</h3>
    
    <div class="row mb4" style="padding-bottom: 10px">
        <div class="col form-inline">
             @include('livewire.partials._perpage')
            <div class="col mb8">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input wire:model="search" class="form-control" type="text" placeholder="Search {{$model}}...">
            </div>
        </div>
    </div>
     @include('livewire.partials._periodselector')
     <div wire:loading>
            <div class="spinner-border text-danger"></div>
    </div>
    <div class="col form-inline">
            <label for="model">Object:</label>
            <select wire:model="model" 
            class="form-control">
                <option value="All">All</option>
                <option value="logins">Logins</option>
                <option value="leads">Leads</option>
                <option value="activities">Activities</option>
                <option value="opportunities">Opportunities</option>
            </select>
        </div>

</div>
