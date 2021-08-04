<div>
    <h2>Leads by Lead Source</h2>
    <p>
        
    </p>
      

    
    
    <div wire:loading>
        <div class="spinner-border"></div>
    </div>
    <div class="row mb-4  form-inline">
        
        @include('livewire.partials._search', ['placeholder'=>'Search Leadsource'])
    </div>
   
    <div class="row mb-4 form-inline">

        @include('livewire.partials._perpage')
        <label>&nbsp;&nbsp;<i class="fas fa-filter text-danger"></i> Filters &nbsp;&nbsp;</label>
        @include('livewire.partials._staleMonths')
        <label for="types">Types:</label>
            <select wire:model="type" 
            class="form-control">
                <option value="All">All</options>
                @foreach ($types as $key)
                    <option value="{{$key}}">{{ucwords($key)}}</option>
                @endforeach
                
            </select>

       
    </div>
    
    @include('leadsource.partials._table')
</div>
