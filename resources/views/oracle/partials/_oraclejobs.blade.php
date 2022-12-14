<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <tr>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('job_profile')" role="button" href="#">
                        Oracle Job
                        @include('includes._sort-icon', ['field' => 'job_profile'])
                </a>
            </th>
            <th class="col-md-2">
                
                    Mapminer Role
                    
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('oracle_job_count')" role="button" href="#">
                    # Oracle Employees
                    @include('includes._sort-icon', ['field' => 'oracle_job_count'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('MapminerUser')" role="button" href="#">
                    # Mapminer User
                    @include('includes._sort-icon', ['field' => 'MapminerUser'])
                </a>
            </th>
            <th class="col-md-2">
                <a wire:click.prevent="sortBy('NotMapminerUser')" role="button" href="#">
                    # Not Mapminer User
                    @include('includes._sort-icon', ['field' => 'NotMapminerUser'])
                </a>
            </th>
            <th>Percentage</th>
        
        
        </tr>
    </thead>
    <tbody>

     @foreach ($jobs as $job)
   
        <tr> 
            <td class="col-md-2">
                <a href="{{route('oraclejobs.show', $job->id)}}"
                    title="Show details of {{$job->profile}} employees"
                    >
                    {{$job->job_profile}}
                </a>
            </td>
            <td class="col-md-2">
                @if($job->mapminerRole)
                    {{$job->mapminerRole->display_name}}
                @endif
            </td>
            <td class="col-md-2">
                {{$job->oracle_job_count}}
            </td>
            <td class="col-md-2">
                {{$job->MapminerUser}}
            </td>
            <td class="col-md-2">
                {{$job->NotMapminerUser}}
            </td>
            <td class="col-md-2">
                {{$job->oracle_job_count ? number_format($job->MapminerUser / $job->oracle_job_count * 100, 2). '%': 'na'}}
            </td>

               
        </tr>
    @endforeach
        
    </tbody>

</table>
<div class="row">
    <div class="col">
        {{ $jobs->links() }}
    </div>

    <div class="col text-right text-muted">
        Showing {{ $jobs->firstItem() }} to {{ $jobs->lastItem() }} out of {{ $jobs->total() }} results
    </div>
</div>