<div>
    <h2>Comparing Oracle HR data to Mapminer </h2>
      <h5>  
        @if($selectRole != 'All')
        
           Oracle {{$roles->where('job_code', $selectRole)->first()->job_profile}}'s
    
        @else
        All Oracle Employees
        @endif
         @if($linked !='All') {{$links[$linked]}}@endif
    </h5>
    <p><a href="{{route('oracle.unmatched')}}">Compare Mapminer data to Oracle data</a></p>
    <p>
        <a href="{{route('oracle.index')}}"
        title="Return to Oracle">
            See all Oracle  Data
        </a>
    </p>
    <p>
        
        <a href="" wire:click.prevent="export">
            <i class="fas fa-file-excel txt-success"></i>
            Export Selection to Excel
        </a>
    </p>
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
                @foreach($roles as $role)
                    <option value='{{$role->job_code}}'>{{$role->job_profile}}</option>
                @endforeach
            </select>
            <label>&nbsp;In Mapminer:&nbsp;</label>
            <select name="linked"
                wire:model="linked"
                class="form-control">
                
                @foreach($links as $id=>$text)
                    <option value='{{$id}}'>{{$text}}</option>
                @endforeach
            </select>
            
             <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
     
            
        </div>

    </div>
    @include('oracle.partials._oraclelist')
    <div class="row">
        <div class="col">
            {{ $users->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} out of {{ $users->total() }} results
        </div>
    </div>

    
</div>

