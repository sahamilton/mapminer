<div>
    <h1>Version History</h1>
    <a href="{{route('versions.create')}}"><i class="fas fa-sync text-success"></i></a>
    <div class="row mb-4 ">
        @include('livewire.partials._search', ['placeholder'=>'Search Git Commits'])
    </div>
    <div wire:loading>
        <div class="spinner-border"></div>
    </div>

    <button class="btn btn-success float-right" 
    title="Export to Excel"
    wire:click='export'>Export <i class="far fa-file-excel"></i></button>
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <label>&nbsp;&nbsp;<i class="fas fa-filter text-danger"></i> Filters &nbsp;&nbsp;</label>
            @include('livewire.partials._periodselector')
        </div>
    </div>
    <table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <a wire:click.prevent="sortBy('commitdate')" role="button" href="#">
                    Commit Date
                    @include('includes._sort-icon', ['field' => 'commitdate'])
            </a>
            <th>Message</th>
            <th>Author</th>     
        </thead>
        @include('git.table');
    </table>
    
    <div class="row">
        <div class="col">
            {{ $versions->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $versions->firstItem() }} to {{ $versions->lastItem() }} out of {{ $versions->total() }} results
        </div>
    </div>


</div>
