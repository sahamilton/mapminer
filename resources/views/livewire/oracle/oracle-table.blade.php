<div>
    
    <h3>
        
        @if($selectRole != 'All')) 
        
           {{$roles->where('id', $selectRole)->first()->display_name}}'s
    
        @else
        All Users
        @endif
         in Mapminer {{$links[$linked]}}
    </h3>
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
            <label><i class="fas fa-filter text-danger"></i>Filters:&nbsp;  </label>
            
            <label>&nbsp;Role:&nbsp;</label>
            <select name="selectRole"
               
                wire:model="selectRole"
                class="form-control">
                <option value='All'>All</option>
                @foreach ($roles as $role)
                    <option value="{{$role->id}}">
                        {{$role->display_name}}
                    </option>
                @endforeach
            </select>
            <label>&nbsp;Service Lines:&nbsp;</label>
            <select name="serviceline"
                wire:model="serviceline"
                class="form-control">
                <option value='All'>All</option>
                @foreach ($servicelines as $key=>$value)
                    <option value="{{$key}}">
                        {{$value}}
                    </option>
                @endforeach
            </select>
            <label>&nbsp;In Oracle:&nbsp;</label>
            <select name="linked"
                wire:model="linked"
                class="form-control">
                
                @foreach($links as $id=>$text)
                    <option value='{{$id}}'>{{$text}}</option>
                @endforeach
            </select>
            <div>
                <div wire:loading>
                    <div class="spinner-border text-danger"></div>
                    
                </div>
                
                @if($selectRole != 'All')
                        <button wire:click='deleteSelected' class="btn btn-danger">
                            Delete All {{$roles->where('id', $selectRole)->first()->display_name}}'s
                        </button>
                @endif
            </div>
        </div>

    </div>
    @include('oracle.partials._matchtable')
    <div class="row">
        <div class="col">
            {{ $users->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
        </div>
    </div>

    
</div>
