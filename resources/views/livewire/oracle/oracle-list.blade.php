<div>
    <h3>Employees in Oracle {{$links[$linked]}}</h3>
    <p>
        <a href="{{route('oracle.unmatched')}}"
        title="See Mapminer data vs Oracle">
            See all Mapminer User Data
        </a>
    </p>
    <p>
        <a href="{{route('oracle.importfile')}}" 
            title="Import Oracle Data">
            <i class="fas fa-sync text-success"></i>Import Oracle Data
        </a>
    
        <a href="{{route('oracle.verify')}}" 
            title="Verify Oracle Data">
            <i class="fas fa-check-double text-warning"></i>Verify Oracle data
        </a>
        <a href="{{route('oracle.manager')}}">
            <i class="fas fa-users text-info"></i>Check Management Structure
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

