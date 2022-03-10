<div>
    <h2>Search for {{$type}}</h2>
    
Address is {{$person->fullAddress()}}
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            <x-form-select 
            name="radius" :options="$range" 
            label="Distance: "
            wire:model='radius' />  
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>
    <div id="branchmap" 
        class="float-right" 
        style="height:400px;width:600px;border:red 
        solid 1px"/>
        
    </div> 
    @include('maps.partials._lwmap')
</div>
