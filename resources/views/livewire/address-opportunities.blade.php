<div>
    @if($owned)

  <div class="float-right mb-4">
    <div class="float-right mb-4">
      <button class="btn btn-info" href="#" wire:click.prevent="addOpportunity({{ $address->id }})">
        Record Opportunity
      </button>
    </div>
  </div>
@endif
<div class="col form-inline mb-4">
  @include('livewire.partials._perpage')

  @include('livewire.partials._search', ['placeholder'=>'Search opportunities'])

  <x-form-select 
    name="status"
    wire:model='status'
    label="Status:"
    :options="$opportunityStatuses" 
    />

</div>           
                  
<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Title</th>
      <th>

        Date Opened

      </th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Company</th>
      <th>Address</th>
      <th>Top 25</th>
      <th>Potential Headcount</th>
      <th>Potential Duration (mos)</th>
      <th>Potential $$</th>
      <th>Last activity</th>
      
    </thead>
      <tbody>
        @foreach ($opportunities as $opportunity)
        
        <tr>
          <td>
           @if($owned)
            
            <a href="{{route('opportunity.show',$opportunity->id)}}" title="Review, edit or delete this opportunity">
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}} </a>
          
              @else
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}}

              @endif
              @if($opportunity->csp == 1)
                  <p class="text-success"><i class="fas fa-clipboard-list "></i> CSP Opportunity</p>
              @endif
          </td>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}
          </td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>

            {{$opportunityStatuses[$opportunity->closed]}}
          
             @if($owned && $opportunity->closed ==0)
           
            <button class="btn btn-danger" wire:click="closeOpportunity({{$opportunity->id}})" >
                    Close</button>
            @endif
          </td>
          <td>
          
            <a href= "{{route('address.show',$opportunity->address->address->id)}}">
              {{$opportunity->address->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->address->fullAddress()}}</td>
          <td>

            <input type="checkbox" id="Top25{{$opportunity->id}}" value="{{$opportunity->id}}" 
            @if($opportunity->Top25)
            checked/><span class="hidden">1</span>
            @endif
            
          </td>
          <td>{{$opportunity->requirements}}</td>
          <td>{{$opportunity->duration}}</td>
          <td>{{$opportunity->value}}</td>
          <td>{{$opportunity->lastActivity->count()>0 ? $opportunity->lastActivity->activity_date->format('Y-m-d') : ''}}</td>
          
        </tr>
        @endforeach

      </tbody>
  

</table>
<div class="row">
        <div class="col">
            {{ $opportunities->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $opportunities->firstItem() }} to {{ $opportunities->lastItem() }} out of {{ $opportunities->total() }} results
        </div>
    </div>

@include('opportunities.partials._modal')
@include('opportunities.partials._lwclosemodal')

</div>
