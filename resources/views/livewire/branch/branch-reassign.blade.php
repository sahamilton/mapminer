<div>
    <h2>Delete {{$oldbranch->branchname}}</h2>

    <div class="bg-warning">Before deleting {{$oldbranch->branchname}} please resolve the following issues.</div>

    <p><strong>{{$oldbranch->branchname}} branch has</strong>:
    <ul>
        <li>{{$oldbranch->allLeads->count()}} leads</li>
        <li>{{$oldbranch->openOpportunities->count()}} open opportunities</li>
        <li>{{$oldbranch->openActivities->count()}} open activities</li>
        <li>{{$oldbranch->branchTeam->count()}} team members</li>
    </ul>
</
</div>
