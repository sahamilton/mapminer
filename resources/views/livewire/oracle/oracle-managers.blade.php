<div>
    <h3>Difference in Manager between {{$types[$type]['title']}}</h3>
    <div class="alert alert-warning alert-block"><p>{{$types[$type]['description']}}</p></div>
    <p><a href="{{route('oracle.index')}}">See all Oracle Data</a></p>
    <p><button wire:click="export">Export Selection to Excel</button></p>
     <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
          
           <label>&nbsp;View Type:&nbsp;</label>
            <select name="types"
               
                wire:model="type"
                class="form-control">
               
                @foreach ($types as $view)
                    <option value="{{$view['id']}}">
                        {{$view['message']}}
                    </option>
                @endforeach
            </select>
            
            <div>
                <div wire:loading>
                    <div class="spinner-border text-danger"></div>
                    
                </div>
                
                
            </div>
        </div>

    </div>
    @switch($type)
        @case('oracle')
            @include('oracle.partials._managertable')
        @break
        @case('mapminer')
            @include('oracle.partials._oraclemanagertable')
            @break
        @case('missing')
        
            @include('oracle.partials._missingmanagertable')
            @break
    @endswitch
    <div class="row">
            <div class="col">
                {{ $users->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
            </div>
        </div>
    </div

