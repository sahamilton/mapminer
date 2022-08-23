<div>

    <h2>Comparing Mapminer Data to Oracle HR Data</h2>
      <h5>  
        @if($selectRole != 'All')
        
          Mapminer {{$roles->where('id', $selectRole)->first()->display_name}}'s
    
        @else
        All Mapminer Users
        @endif
          @if($linked !='All') {{$links[$linked]}}@endif
    </h5>
    <p><a href="{{route('oracle.list')}}">Compare Oracle data to Mapminer data</a>
    <p><a href="{{route('oracle.index')}}">See all Oracle Data</a></p>
    <p>
        
        <a href="" wire:click.prevent="export">
            <i class="far fa-file-excel txt-success"></i>Export Selection to Excel
        </a>
    </p>
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
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
                    
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
                
                @if(! $showConfirmation)
                    @if($selectRole != 'All')
                        <button wire:click='deleteSelected' class="btn btn-danger">
                            Delete All {{$roles->where('id', $selectRole)->first()->display_name}}'s
                        </button>
                    @endif
                @else

                    <div class="alert alert-warning alert-block" >
                        Are you Really REALLY sure you want to delete these {{$roles->where('id', $selectRole)->first()->display_name}}'s?
                        <button wire:click.prevent='confirmDeleteUsers()' class="btn btn-danger">Confirm Delete</button>
                        <button wire:click.prevent='cancelDeleteUsers()' class="btn btn-secondary">Cancel</button>

                    </div>
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
