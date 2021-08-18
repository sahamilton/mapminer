<div>
    <h2>Managers Mapminer Stats</h2>
    <h3>For the period from {{session('period')['from']->format('Y-m-d')}} to {{session('period')['to']->format('Y-m-d')}}</h3>
    
    <div class="row mb4" style="padding-bottom: 10px">
        <div class="col form-inline">
             @include('livewire.partials._perpage')
            <div class="col mb8">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input wire:model="search" class="form-control" type="text" placeholder="Search managers...">
            </div>
        </div>
    </div>
     @include('livewire.partials._periodselector')
     <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>

    <table class="table table-striped">
        <thead>
            <th>
            <a wire:click.prevent="sortBy('lastname')" 
                role="button" href="#">
                    Manager
                    @include('includes._sort-icon', ['field' => 'lastname'])
                </a>
            </th>
            @foreach ($fields as $key=>$field)
                <th>
                    <a wire:click.prevent="sortBy('{{$key}}')" 
                    role="button" href="#">
                        {{$field}}
                        @include('includes._sort-icon', ['field' => '{{$key}}'])
                    </a>
                </th>
            @endforeach
        </thead>
        <tbody>
            @foreach ($data as $item)
                
                <tr>
                    <td><a href ="{{route('usertracking.show', $item->id)}}">{{$item->fullName()}}</a></td>
                     @foreach ($fields as $key=>$field)
                        <td>{{$item->$key}}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>

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





