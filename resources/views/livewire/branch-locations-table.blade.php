<div>
    <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    <table 
        class='table table-striped table-bordered table-condensed table-hover'>
        <thead> 
            
            <th>Business Name</th>
            <th>Industry</th> 
            <th>Street </th> 
            <th>City </th> 
            <th>State </th> 
            <th>ZIP </th> 
            <th>Distance from Branch (mi)</th>
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
