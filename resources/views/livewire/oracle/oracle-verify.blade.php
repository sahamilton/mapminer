<div>
    <h2>Oracle HR Data compared to Mapminer</h2>
    <h4>{{$title}}</h4>
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
            <i class="fas fa-search"></i> <input wire:model="search" class="form-control" type="text" placeholder="Search users...">
        </div>
    </div>
    <div>
        
        @switch($showView)
            @case('emails')
                @include('oracle.partials._verifyemail')
            @break
            @case('roles')
                @include('oracle.partials._verifyrole')
            @break
        @endswitch
   
    </div>
</div>
