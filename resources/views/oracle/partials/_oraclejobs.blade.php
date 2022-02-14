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

        
        
        </tr>
    </thead>
    <tbody>

     @foreach ($jobs as $job)
   
        <tr> 
            <td class="col-md-2">
                {{$job->job_profile}}
            </td>
            <td class="col-md-2">
                @if($job->mapminerRole)
                    {{$job->mapminerRole->display_name}}
                @endif
            </td>
            <td class="col-md-2">
                {{$job->oracle_job_count}}
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