<div>
    <h2>{{$myBranches[$branch_id]}}</h2>
    <p><a href="{{route('branchdashboard.show', $branch_id)}}">
    <i class="fas fa-tachometer-alt"></i>
     Return To Branch {{$myBranches[$branch_id]}} Dashboard</a></p>
   
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._branchselector')
            @include('livewire.partials._search', ['placeholder'=>'Search Leads'])
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
       <label><i class="fas fa-filter text-danger"></i>&nbsp;&nbsp;Filter&nbsp;&nbsp;</label>
        <div class="col form-inline">
            <label for="distance">Distance:</label>
            <select wire:model="distance" 
            class="form-control">
                @foreach ($distances as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
                
            </select>
        </div>
       <div class="col form-inline">
            <label for="searchaddress:">Address:</label>
            <input type="text" value="{{$searchaddress}}" wire:model="searchaddress" />
        </div>
    </div>
    {{$leads->total()}}
</div>
