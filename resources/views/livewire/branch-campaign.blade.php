<div>
    <h2>{{$branch->branchname}}</h2>
    <h4> Campaign {{ucwords($view)}} for the {{$campaign->title}}</h4>
    <p>{{$company_id != 'All' ? $companies[$company_id] : ''}}</p>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
         
            @include('livewire.partials._search', ['placeholder'=>'Search '])
            <div wire:loading class="spinner-border text-danger" role="status">
              <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            
            <div class="col form-inline">
                <label for="view">View:</label>
                <select wire:model="view" 
                class="form-control">
                
                @foreach ($views as $key)
                    <option value="{{$key}}">{{ucwords($key)}}</option>
                @endforeach
                    
                </select>
            </div>
        </div>
        <div class="col form-inline">
            
            <div class="col form-inline">
                <label for="view">Company:</label>
                <select wire:model="company_id" 
                class="form-control">
                <option value="All">All</option>
                    @foreach ($companies as $key=>$value)
                        <option value="{{$key}}">{{ucwords($value)}}</option>
                    @endforeach
                    
                </select>
            </div>
        </div>
    </div>
        @if($data->count() > 0)
            @switch($view)
            @case('leads')

                @include('campaigns.partials._leads')
              

            @break

            @case('activities')
                @include('campaigns.partials._activities')
            @break

            @case('opportunities')
                @include('campaigns.partials._opportunities')
            @break

            @endswitch
            <div class="row">
                <div class="col">
                    {{ $data->links() }}
                </div>

                <div class="col text-right text-muted">
                    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} out of {{ $data->total() }} results
                </div>
            </div>
        @else
        <div class="row">
            <div class= "alert alert-warning">
                <p>There currently are no {{$campaign->title}} campaign {{$view}} at {{$branch->branchname}}</p>

            </div>
        </div>
        @endif
        
    </div>

</div>
