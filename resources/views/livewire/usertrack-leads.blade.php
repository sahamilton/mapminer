<div>
<p>for the period from {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</p>

    <div class="row mb-4">
        @include('livewire.partials._perpage')
        @include('livewire.partials._periodselector')
        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search leads...">
        </div>
    </div>

    <div class="row">
        <table class='table table-striped table-bordered table-condensed table-hover'>
            <thead>
                <tr>
                    <th>
                        <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                            Business
                            @include('includes._sort-icon', ['field' => 'businessname'])
                        </a>

                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('street')" role="button" href="#">
                            Street
                            @include('includes._sort-icon', ['field' => 'street'])
                        </a>
                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('city')" role="button" href="#">
                            City
                            @include('includes._sort-icon', ['field' => 'city'])
                        </a>
                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('state')" role="button" href="#">
                            State
                            @include('includes._sort-icon', ['field' => 'state'])
                        </a>
                    </th>
                    <th>
                        <a wire:click.prevent="sortBy('state')" role="button" href="#">
                            Source
                            @include('includes._sort-icon', ['field' => 'lead_source_id'])
                        </a>
                       
                    </th>
                    
                    <th>
                    <a wire:click.prevent="sortBy('last_activity_id')" role="button" href="#">
                            Last activity
                            @include('includes._sort-icon', ['field' => 'last_activity_id'])
                        </a>

                   
                </th>
                <th>Branch</th>
                <th>
                    <a wire:click.prevent="sortBy('dateAdded')" role="button" href="#">
                    Lead Added / Updated
                     @include('includes._sort-icon', ['field' => 'dateAdded'])
                        </a>
                </th>
                
                </tr>
            </thead>
            <tbody>
               @foreach($leads as $lead)

 <tr>
        <td>
            <a href="{{route('address.show',$lead->id)}}">
                {{$lead->businessname}}
            </a>
        </td>

        <td>{{$lead->street}}</td>
        <td>{{$lead->city}}</td>
        <td>{{$lead->state}}</td>
        <td>
            @if($lead->leadsource)
             {{$lead->leadsource->source}}
            @endif
        </td>
        
        <td>
            @if($lead->lastActivity)
                {{$lead->lastActivity->activity_date->format('Y-m-d')}}        
            @endif
        </td>
        <td>
            @foreach ($lead->assignedToBranch as $branch)
                {{$branch->id}}
            @endforeach
        </td>
        <td>
            @if($lead->dateAdded)
                {{max($lead->created_at, $lead->updated_at)->format('Y-m-d')}}
            @endif
        </td>
        
    </tr>
@endforeach
           
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col">
            {{ $leads->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $leads->firstItem() }} to {{ $leads->lastItem() }} out of {{ $leads->total() }} results
        </div>
    </div>
</div>
