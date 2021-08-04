<div>
    <h2>{{ucwords($type)}} Campaign Documents</h2>
    <div class="float-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Create New Campaign Document</button>
   </div>
    <div class="tab-content">
        <div class="row mb4" style="padding-bottom: 10px"> 
            <div class="col form-inline">
                @include('livewire.partials._perpage')
                @include('livewire.partials._search', ['placeholder'=>'Search documents '])

            </div>
        
            <div wire:loading>
                <div class="spinner-border"></div>
            </div>
            <div class="col form-inline">
                 <i class="fas fa-filter text-danger"></i>
                 <label for="type">Type:</label>
                <select wire:model="type" 
                class="form-control">
                   <option value="All">All</options>
                   @foreach ($types as $value)
                        <option value='{{$value}}'>{{ucwords($value)}}</options>
                   @endforeach
                    
                </select>
            </div>
            
        </div>
        
        @include('campaigns.documents._modalform')
       
        @include('campaigns.documents._list')

        </div>
    </div>
</div>
