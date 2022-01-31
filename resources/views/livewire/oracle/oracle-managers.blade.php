<div>
    <h3>Difference in Manager between Oracle & Mapminer</h3>
    <p><a href="{{route('oracle.index')}}">See all Oracle Data</a></p>
    <p><button wire:click="export">Export Selection to Excel</button></p>
     <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            <label><i class="fas fa-filter text-danger"></i>Filters:&nbsp;  </label>
           
            
            <div>
                <div wire:loading>
                    <div class="spinner-border text-danger"></div>
                    
                </div>
                
                
            </div>
        </div>

    </div>
    @include('oracle.partials._managertable')
    

</div>
