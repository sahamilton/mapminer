<div>
    <h2>{{$campaign->title}} Campaign</h2>
    Sorting by {{$sortField}}
    <h4>{{ucwords($type)}} Summary</h4>
    <p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>
    <div class="float-right">
        <a href="{{route('campaigns.edit', $campaign->id)}}" class="btn btn-info">Edit Campaign</a>
   </div>
   
    <p><strong>Status:</strong>{{$campaign->status}}</p>

    <p><a href="{{route('campaigns.launch', $campaign->id)}}" class="btn btn-warning">Launch / Relaunch Campaign</a></p>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search '])
            <div class="col form-inline">
            <label>View By:</label>
            <select class="form-control" wire:model="type">
                <option value="company">Company</option>
                <option value = 'branch'>Branch</option>
            </select>
        </div>
        </div>
    </div>


       
    <div wire:loading class="spinner-border text-danger" role="status">
          <span class="sr-only">Loading...</span>
    </div>
 
        
    

    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('name')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                    {{ucwords($type)}}
                    @include('includes._sort-icon', ['field' => 'name'])
                </a>
            </th>    
            <th>
                <a wire:click.prevent="sortBy('assigned_count')" 
                role="button" href="#" 
                wire:loading.class="bg-danger">
                    Assigned Locations
                    @include('includes._sort-icon', ['field' => 'assigned_count'])
                </a>
            </th>
            <th>
                @if($type=="company")
                
                    <a wire:click.prevent="sortBy('unassigned_count')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                        UnAssigned Locations
                        @include('includes._sort-icon', ['field' => 'unassigned_count'])
                    </a>
                @else
                    Assignable
                @endif
            </th>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{$type=='company' ? $item->companyname : $item->branchname}}</td>
                <td>{{$item->assigned_count}}</td>
                <td>
                    @if($type == 'company')
                        {{$item->unassigned_count}}
                    @else
                
                        {{ $assignable->where('branchname', $item->branchname)->sum('assignable') }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
                <tr>
                    <th></th>
                    
                    <th>{{$summarycount['assigned']}}</th>
                    <th>{{$type=='branch' ?  $assignable->sum('assignable') : $summarycount['unassigned']}}</th>

                </tr>

        </tfoot>
    </table>
    <div class="row">
            <div class="col">
                {{ $data->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} out of {{ $data->total() }} results
            </div>
        </div>
    </div>
</div>
