<div>
    <h2>{{ucwords($status)}} Branch Sales Campaigns</h2>

    <div class="tab-content">
        <div class="row mb4" style="padding-bottom: 10px"> 
            <div class="col form-inline">
                @include('livewire.partials._perpage')
                @include('livewire.partials._search', ['placeholder'=>'Search campaigns '])

            </div>
        
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
            <div class="col form-inline">
                 <i class="fas fa-filter text-danger"></i>
                 <label for="status">Status:</label>
                <select wire:model="status" 
                class="form-control">
                    <option value="All">All</options>
                   @foreach ($statuses as $value)
                        <option value='{{$value}}'>{{ucwords($value)}}</options>
                   @endforeach
                    
                </select>
            </div>
            
        </div>
          
        @include('campaigns.partials._list')
        <div class="row">
            <div class="col">
                {{ $campaigns->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $campaigns->firstItem() }} to {{ $campaigns->lastItem() }} out of {{ $campaigns->total() }} results
            </div>
        </div>
    </div>
</div>
