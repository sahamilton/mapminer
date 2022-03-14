<div>
    <h2>{{$branch->branchname}}</h2>
    <h4>Unclaimed leads within {{$distance}} miles of {{$searchaddress}}</h4>
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
            <input class="form-control" type="text" name="searchaddress" wire:model.lazy="searchaddress" value="{{$searchaddress}}" />

            <button wire:click="updateSearchAddress()" class="btn btn-success">Update</button>
        </div>

    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>  
                <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'businessname'])
                </a>
                   
            </th>
            <th>Address</th>

            <th>Account</th>
            <th>Type</th>
            </th>
            <th>  
               <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                    Distance
                     @include('includes._sort-icon', ['field' => 'distance'])
                </a>
                   
            </th>
            <th>Added to Mapminer</th>
        </thead>
        <tbody>
            @foreach ($leads as $lead)
            <tr>
                <td>
                    <a href="{{route('address.show', $lead->id)}}">{{$lead->businessname}}</a>
                </td>
                <td>{{$lead->fullAddress()}}</td>
                <td>
                    @if($lead->company)
                        <a href="{{route('company.show', $lead->company->id)}}">
                            {{$lead->company->companyname}}
                        </a>
                    @endif
                </td>
                <td>{{$lead->type}}</td>
                <td>{{number_format($lead->distance,1)}} miles</td>
                <td>{{$lead->created_at ? $lead->created_at->format('Y-m-d') : ''}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col">
            {{ $leads->links() }}
        </div>

        
    </div>

@include('partials.scripts._autofill')
</div>
