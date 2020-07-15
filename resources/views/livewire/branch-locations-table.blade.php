<div>
    <table 
        class='table table-striped table-bordered table-condensed table-hover'>
    <thead> 
        <th>Company Name</th> 
        <th>Industry Vertical</th>
        <th>Street </th> 
        <th>City </th> 
        <th>State </th> 
        <th>ZIP </th> 
    </thead>
    <tbody>
        @include('branches.partials._branchlocations')
    </tbody>
    <div class="row">
            <div class="col">
                {{ $addresses->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $addresses->firstItem() }} to {{ $addresses->lastItem() }} out of {{ $addresses->total() }} results
            </div>
        </div>
    </div>

</div>