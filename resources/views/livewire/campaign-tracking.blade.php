<div>
    <h2>{{$campaign->title}} Campaign</h2>

    <h4>{{ucwords($type)}} Tracking Summary</h4>
    <p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>

    <p><strong>Status:</strong>{{ucwords($campaign->status)}}</p>

    
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')

            @include('livewire.partials._search', ['placeholder'=>'Search '])
            <div wire:loading class="spinner-border text-danger" role="status">
                  <span class="sr-only">Loading...</span>
            </div>
            <div class="col form-inline">
            <label>View By:</label>
            <select class="form-control" wire:model="type">
                <option value="company">Company</option>
                <option value = 'branch'>Branch</option>
            </select>
        </div>
    </div>


    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('name')" 
                    role="button" href="#">
                    {{ucwords($type)}}
                    @include('includes._sort-icon', ['field' => 'name'])
                </a>
            </th>    
            
            @foreach ($fields as $field)
                <th>
                    <a wire:click.prevent="sortBy('{{$field}}')" 
                    role="button" href="#">
                        {{ucwords(str_replace("_", " ", $field))}}
                        @include('includes._sort-icon', ['field' => '{{$field}}'])
                    </a>
                </th>
            @endforeach
        </thead>
        <tbody>
            @foreach ($data as $item)

            <tr>
                <td>
                    @if($type=='company') 
                    {{$item->companyname}}
                    @else
                    <a href="{{route('branchcampaign.show',[$campaign->id, $item->id])}}" >{{$item->branchname}}</a>
                    @endif
                </td>
                @foreach ($fields as $field)
                    <td>{{$item->$field ? $item->$field : '0'}}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th></th>
            
                @foreach ($fields as $field)
                    <th>{{$summarycount[$field]}}</th>
                @endforeach
            
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
