<div>
    
    <h2>
        @if ($this->person) 
            {{$person->completeName}}'s Teams

        @else

            {{$roles[$role_id]}}

        @endif
         Mapminer Statistics </h2>
    <h4>For the period {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}</h4>
    <div class="row mb-4">
        <div class="col form-inline">
             @include('livewire.partials._periodselector')
             @include('livewire.partials._perpage')
             <x-form-select name="role_id"
                wire:model="role_id"
                label="Roles:"
                :options="$roles"
                />
             @include('livewire.partials._search', ['placeholder'=>'Search Managers'])
             
        </div>

    </div>
    <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
    </div>
    <table class="table">
        <thead>
            <th>Per branch / Per Day</th>
            <tr />
            <th>
                <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                    Manager
                @include('includes._sort-icon', ['field' => 'lastname'])
            </th>
            <th>Role(s)</th>
            <th>Reports To</th>
            @foreach ($fields as $field=>$label)
                <th>
                    <a wire:click.prevent="sortBy('{{$field}}')" role="button" href="#">
                       {{$label}}
                        @include('includes._sort-icon', ['field' => '{{$field}}'])
                    </a>
               </th>

            @endforeach
        </thead>
        <tbody>
            @foreach ($people as $person)

            <tr>
                <td>
                    @if(! $person->userdetails->hasRole('branch_manager'))
                        <a href="#" wire:click.prevent= "setManager({{$person->id}})" >{{$person->completeName}}</a>
                    
                    @else
                        
                        <a href="{{route('newdashboard.manager', $person->id)}}" 
                                title="Review {{$person->completeName}}'s dashboard">
                            {{$person->completeName}}
                        </a>
                    @endif

                </td>
                <td>
                    @foreach($person->userdetails->roles as $role)
                        {{$role->display_name}}
                    @endforeach
                </td>
                <td>
                    @if($person->ReportsTo->reports_to)
                    <a href='#' wire:click.prevent="setManager({{$person->reportsTo->reports_to }})">
                        {{$person->reportsTo->completeName}}
                    </a>
                    @endif
                </td>
                @foreach($fields as $field=>$label)
                    <td>{{number_format($person->$field,2)}}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $people->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $people->firstItem() }} to {{ $people->lastItem() }} out of {{ $people->total() }} results
        </div>

    </div>
</div>
