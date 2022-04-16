<div>
   <h2>Oracle Job Profiles Matched to Mapminer Roles</h2>
    <h4></h4>
    <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    <p><a href="{{route('oracle.index')}}">Return to Oracle Data</a></p>
    <div class="row mb-4">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <i class="fas fa-search"></i> 
            <input wire:model="search" class="form-control" type="text" placeholder="Search job profiles...">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col form-inline">
            
            <i class="fas fa-filter"></i> 
            <select wire:model="select" class="form-control" >
                @foreach ($options as $option)
                    <option value="{{$option}}">{{$option}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @include('oracle.partials._oraclejobs')
</div>
