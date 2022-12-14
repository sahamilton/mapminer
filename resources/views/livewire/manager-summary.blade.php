<div>

    <h2>Branch Summaries</h2>

    <p class="bg-warning">For the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
    <div class="row form-inline">
      @include('livewire.partials._perpage')
      @include('livewire.partials._periodselector')
    </div>
    
    <div wire:loading>
        <div class="spinner-border text-danger"></div>
    </div>
    <table 
    class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>
        
          <a wire:click.prevent="sortBy('branchname')" role="button" href="#">
             Branch
              @include('includes._sort-icon', ['field' => 'branchname'])
          </a>
        
      </th>
      <th>Manager</th>
      
      <th>
        <a wire:click.prevent="sortBy('open_opportunities')" role="button" href="#"
        >

          Open Opportunities
          @include('includes._sort-icon', ['field' => 'branchname'])
        </a>
      </th>
      <th>
        <a wire:click.prevent="sortBy('won_opportunities')" role="button" href="#">
          Won
          @include('includes._sort-icon', ['field' => 'won_opportunities'])
        </a>
      </th>
      <th>
         <a wire:click.prevent="sortBy('open_opportunities')" role="button" href="#">
          Lost
          @include('includes._sort-icon', ['field' => 'lost_opportunities'])
        </a>
      </th>
      <th class="tip" title="Leads with activities in this period">
        <a wire:click.prevent="sortBy('active_leads')" role="button" href="#">
          Active Leads
            @include('includes._sort-icon', ['field' => 'active_leads'])
        </a>
      </th>
      <th class="tip" title="Activities in this period">
          <a wire:click.prevent="sortBy('activities_count')" role="button" href="#">
            Period Activities
            @include('includes._sort-icon', ['field' => 'activities_count'])
          </a>
      </th>
      
    </thead>
      <tbody>
        @foreach ($branches as $branch)
      
          <tr>
            <td>
              <a href="{{route('branchdashboard.show',$branch->id)}}">{{$branch->branchname}}</a>
            </td>
            
            <td>
              @foreach ($branch->manager as $manager)
                <li>{{$manager->fullName()}}</li>
              @endforeach
            </td>
             <td align="center">
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->open_opportunities}}
              </a>
            </td>
            <td align="center">
              @if($branch->won_opportunities >0)
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->won_opportunities}}
              </a> 
              @else
               0 
               @endif
            </td>
            <td  align="center"> 
              @if($branch->lost_opportunities >0)
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->lost_opportunities}}
              </a> 
            @else 
              0 
            @endif
          </td>

            <td align="center">
              <a href="{{route('lead.branch',$branch->id)}}"> 
                {{$branch->active_leads}}
              </a>
            </td>
            
           
            <td align="center">
              <a href="{{route('activity.branch',$branch->id)}}">
                 {{$branch->activities_count}}
              </a>
            </td>
         
        </tr>
       @endforeach
  </tbody>
  <tfoot>
    <th colspan=2></th>
    <td align="center">{{$branches->sum('open_opportunities')}}</td>
    <td align="center">{{$branches->sum('won_opportunities')}}</td>
    <td align="center">{{$branches->sum('lost_opportunities')}}</td>
    <td align="center">{{$branches->sum('active_leads')}}</td>
    <td align="center">{{$branches->sum('activities_count')}}</td>
  </tfoot>
</table>
<div class="row">
        <div class="col">
            {{ $branches->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} out of {{ $branches->total() }} results
        </div>
    </div>
</div>
