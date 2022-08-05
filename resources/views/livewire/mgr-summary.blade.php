<div>
    <h2>{{ucwords($summaryview)}}</h2>
    <p class="bg-warning">For the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>
    <p>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Branches'])
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
       
        
        <div class="col form-inline">
            <label for="status">View:</label>
            <select wire:model="summaryview" 
            class="form-control">
                @foreach ($views as $value)
                    <option value="{{$value}}">{{ucwords($value)}}</option>
                @endforeach
                
            </select>
        </div>
    </div>

    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('id')" 
                role="button" href="#">
                    Branch
                    @include('includes._sort-icon', ['field' => 'id'])
                </a>
            </th>
            <th>Manager</th>
            </th>
            @foreach($displayFields as $key=>$value)
            <th>
                <a wire:click.prevent="sortBy('{{$value}}')" 
                role="button" href="#">
                    {{ucwords(str_replace('_', ' ', $value))}}
                    @include('includes._sort-icon', ['field' => '{{$value}}'])
                </a>
            </th>
            

            @endforeach
         
        </thead>
        <tbody>

        @foreach ($branches as $branch)
        
        
            <tr>
               <td>
                    <a href="{{route(implode('',$route), $branch->id)}}">
                        {{$branch->branchname}}
                    </a>
                </td>
                <td>
                    @foreach ($branch->manager as $manager)
                    {{$manager->fullName}}{{! $loop->last ? ", " :''}}

                    @endforeach
               </td>
                @foreach($displayFields as $value)
                    @if(isset($value) && strpos($value, 'value'))
                        <td align='right'>${{number_format($branch->$value,0)}}</th>
                    @else
                        <td align='center'>{{$branch->$value}}</th>
                    @endif
                @endforeach

            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="row">
            <div class="col">
                {{ $branches->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} out of {{ $branches->total() }} results
            </div>
        </div>
    </div>
</div>
