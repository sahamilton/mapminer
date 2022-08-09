<div class="row">
  
  <div id="series_chart_div" 
    style="width: 80%;height:400px;border:solid 1px #aaaaaa;margin:5px;"> 
    <h4>Wins vs Sales Appts</h4>
    <p><b>"TAHA" Report</b></p>

    @include('charts._bubble')

  </div>
  <div>
    <table class="table">
      <thead>
        <th>
          <a wire:click.prevent="sortBy('id')" 
                  role="button" 
                  href="#" 
                 >
                      Branch
              @include('includes._sort-icon', ['field' => 'id'])
          </a>
          
        </th>
        <th>
          <a wire:click.prevent="sortBy('sales_appointment')" 
                    role="button" 
                    href="#" 
                   >
                        Sales Appointments
                @include('includes._sort-icon', ['field' => 'sales_appointment'])
            </a>
        </th>
        <th>
          <a wire:click.prevent="sortBy('won_opportunities')" 
                    role="button" 
                    href="#" 
                   >
                        # Opportunities Won
                @include('includes._sort-icon', ['field' => 'won_opportunities'])
          </a>
        </th>
        <th>
          <a wire:click.prevent="sortBy('won_value')" 
                    role="button" 
                    href="#" 
                   >
                        $ Opportunities Won
                @include('includes._sort-icon', ['field' => 'won_value'])
          </a>
        </th>
      </thead>
      <tbody>
        @foreach($data['branches'] as $branch)
          <tr>
            <td>
              <a href="{{route('branchdashboard.show', $branch->id)}}"
                title="See branch {{$branch->branchname}}'s dashboard">{{$branch->branchname}}
              </a>
            </td>
            <td class="text-center">{{$branch->sales_appointment}}</td>
            <td class="text-center">{{$branch->won_opportunities}}</td>
            <td class="text-right"> ${{number_format($branch->won_value,0)}}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
          <th>Totals:</th>
          <th class="text-center">{{$data['branches']->sum('sales_appointment')}}</th>
          <th class="text-center">{{$data['branches']->sum('won_opportunities')}}</th>
          <th class="text-right">${{number_format($data['branches']->sum('won_value'),0)}}</th>
      </tfoot>
    </table>
  </div>  
  <div class="row">
        <div class="col">
            {{ $data['branches']->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $data['branches']->firstItem() }} to {{ $data['branches']->lastItem() }} out of {{ $data['branches']->total() }} results
        </div>
    </div>  
</div>