<div>

    <h2>Branch Summaries</h2>
    <!-- account name -->
    <p class="bg-warning">For the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>
    
    <div class="col form-inline">
      @include('livewire.partials._periodselector')
      @include('livewire.partials._perpage') 
      @include('livewire.partials._companyselector')
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
      
      <th class="tip" title="Branch leads">
        Leads

      </th>
      <th class="tip" title="Branch leads worked this period">
        Touched
          
      </th>
      <th  class="tip" title="Branch leads worked this period">>
         Activities
      </th>
      <th class="tip" title="Leads with activities in this period">
        New Opportunities
      </th>
      <th class="tip" title="Activities in this period">
         Open Opportunities
      </th>
      <th class="tip" title="Activities in this period">
         Open Value
       </th>
       <th class="tip" title="Activities in this period">
         Won Opportunities
      </th>
      <th class="tip" title="Activities in this period">
         Won Value
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
              
                {{$branch->worked_leads}}
              </a>
            </td>
            <td align="center">
              
              {{$branch->touched_leads}}
               
            </td>
            

            <td align="center">
              {{$branch->activitycount}}
            </td>
            
           
            <td align="center">
              {{$branch->new_opportunities}}
            </td>
            <td align="center">
              {{$branch->opportunities_open}}
            </td>
            <td align="center">
              {{$branch->open_value}}
            </td>
            <td align="center">
              {{$branch->won_opportunities}}
            </td>
            <td align="center">
              {{$branch->won_value}}
            </td>
         
        </tr>
       @endforeach
  </tbody>
  
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
