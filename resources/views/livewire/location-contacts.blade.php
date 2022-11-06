<div>
    <h2>{{$branch->branchname}} Contacts</h2>
    <div class="row mb4" style="padding-bottom: 10px">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._branchselector')
            @include('livewire.partials._search', ['placeholder'=>'Search Contacts'])
        </div>
    
    </div>
    
    <div class="row mb-4 ">
        <div class="col form-inline">

            <i class="fas fa-filter text-danger"> </i>

            <label>With Phone / Email </label>
            <select wire:model="filter" 
            class="form-control">
                <option value="All">All</option>
                <option value='email'>with Email</option>
                <option value='contactphone'>with Phone</option>
            </select>
            <div wire:loading>
                <div class="spinner-border text-danger"></div>
            </div>
        </div> 

    
        <div class="float-right">
                <a href="{{route('contacts.export', [$branch->id, 'filter'=>$filter])}}">
                    <button class="btn btn-success">
                        Export Contacts
                    </button> 
            </a> 
        </div>
    </div>
    <table  style="margin-top:20px" class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'businessname'])
                </a>
                    
            </th>
            <th>
                <a wire:click.prevent="sortBy('city')" role="button" href="#">
                    City
                    @include('includes._sort-icon', ['field' => 'city'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('state')" role="button" href="#">
                    State
                    @include('includes._sort-icon', ['field' => 'state'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('fullname')" role="button" href="#">
                    Contact
                    @include('includes._sort-icon', ['field' => 'fullname'])
                </a>
            </th>
            <th>Title</th>
            <th>Phone</th>
            <th>EMail</th>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
          
            <tr>
                <td>
                    
                    <a href="{{route('address.show', $contact->address_id)}}">
                        {{$contact->businessname}}
                    </a>
                   
                </td>
                <td>{{$contact->city}}</td>
                <td>{{$contact->state}}</td>
                <td>
                   <a href="{{route('contacts.show', $contact->id)}}"> {{$contact->completeName}}</a>
                </td>
                <td>{{$contact->title}}</td>
                <td><a href="tel:{{$contact->phoneNumber}}">{{$contact->phoneNumber}}</a></td>
                <td>{!! $contact->fullEmail !!}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    <div class="row">
            <div class="col">
                {{ $contacts->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} out of {{ $contacts->total() }} results
            </div>
        </div>
    </div>
</div>

