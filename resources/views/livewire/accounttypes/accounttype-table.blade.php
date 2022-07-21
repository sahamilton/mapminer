<div>
    <div class="container">
    <h2>{{$accounttype != 'All' ? $accounttypes[$accounttype] : 'All' }} Account Types</h2>
    <div class="float-right">
           <a href="{{route('accounttype.create')}}" 
               type="button" 
               class="btn btn-info" 
               >
               Add Account Type
           </a> 
           
        </div>
    <div class="row mb4" style="padding-bottom: 10px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Companies'])
            
        </div>
    </div>
     <div class="row mb-4">
        <div class="col form-inline">
            <x-form-select label="Account Type:" wire:model="accounttype" name="accounttype" :options="$accounttypes" />
            <x-form-select label="Manager:" wire:model="manager"  name="manager" :options="$managers" />

            
        </div>
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <tr>
                <th>
                    <a wire:click.prevent="sortBy('companyname')" 
                    role="button" href="#" 
                    wire:loading.class="bg-danger">
                        Account
                        @include('includes._sort-icon', ['field' => 'companyname'])
                    </a>
                </th>
                <td>Manager</td>
                <th>Locations</th>
                <th>Locations Assigned to Branches</th>
                <th>Locations Worked</th>
                <th>Locations with Opportunities</th>
            </tr>
        </thead>
        <tbody>

        @foreach( $companies as $company)

            <tr>
                <td>
                    <a href="{{ route('company.show', $company->id) }}" >
                        {{$company->companyname}}
                    </a>
                </td>
                <td>
                    @if($company->managedBy) 
                        <a href="{{route('user.show', $company->managedBy->user_id)}}"> 
                            {{$company->managedBy->complete_name}}
                        </a>
                    @endif
                </td>
                <td>{{$company->locations_count}}</td>
                <td>{{$company->leads}}</td>
                <td>{{$company->worked}}</td>
                <td>{{$company->opportunities}}</td>
                
            </tr>
        @endforeach

        </tbody>
    </table>

    <div class="row">
        <div class="col">
            {{ $companies->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} out of {{ $companies->total() }} results
        </div>
    </div>
</div>