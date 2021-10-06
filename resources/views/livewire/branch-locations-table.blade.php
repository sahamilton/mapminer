<div>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search addresses '])
            <div class="col mb8">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-arrows-alt-h"></i></span>

                    <input wire:model="distance" class="form-control" type="text" placeholder="distance"> Miles from branch
                </div>
            </div>

        </div>
    
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
        
    <table 
        class='table table-striped table-bordered table-condensed table-hover'>
        <thead> 
            
            <th>
                <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                    Business Name
                    @include('includes._sort-icon', ['field' => 'businessname'])
                </a>
            </th>
            <th>Industry</th> 
            <th>Street </th> 
            <th>City </th> 
            <th>State </th> 
            <th>ZIP </th> 
            <th>
                <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                   Distance from Branch (mi)
                    @include('includes._sort-icon', ['field' => 'distance'])
                </a>
            </th>
        </thead>
        <tbody>
            @include('branches.partials._branchlocations')
        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $addresses->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $addresses->firstItem() }} to {{ $addresses->lastItem() }} out of {{ $addresses->total() }} results
        </div>

    </div>

</div>
