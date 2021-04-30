<table class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
   
    <th>
        <a wire:click.prevent="sortBy('source')" role="button" href="#">
            Lead Source
            @include('includes._sort-icon', ['field' => 'source'])
        </a>

    </th>    
    
    <th>
    <a wire:click.prevent="sortBy('datefrom')" role="button" href="#">
          Date From
          @include('includes._sort-icon', ['field' => 'datefrom'])
      </a>
    </th>
    <th>
    <a wire:click.prevent="sortBy('dateto')" role="button" href="#">
          Date To
          @include('includes._sort-icon', ['field' => 'dateto'])
      </a>
    </th>

    
    <th>
      <a wire:click.prevent="sortBy('branchleads_count')" role="button" href="#">
          Assigned Leads
          @include('includes._sort-icon', ['field' => 'branchleads_count'])
      </a>
    </th>
    <th>
      <a wire:click.prevent="sortBy('staleleads')" role="button" href="#">
          Stale Leads
          @include('includes._sort-icon', ['field' => 'staleleads'])
      </a>
    </th>

  </thead>
  <tbody>
    @foreach ($leadsources as $leadsource)
    <tr>
      
  
      <td>
          <a href="{{route('leadsource.show',$leadsource->id)}}">
           {{$leadsource->source}}
         </a>
      </td> 
      <td>{{$leadsource->datefrom->format('Y-m-d')}}</td>
      <td>{{$leadsource->dateto->format('Y-m-d')}}</td>
     
      
      <td class="text-right">
        {{number_format($leadsource->branchleads_count,0)}}
      </td>
      <td class="text-right">
        {{number_format($leadsource->staleleads,0)}}
      </td>
     
    </tr>
    @endforeach
  </tbody>
</table>
 <div class="row">
        <div class="col">
            {{ $leadsources->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $leadsources->firstItem() }} to {{ $leadsources->lastItem() }} out of {{ $leadsources->total() }} results
        </div>
    </div>