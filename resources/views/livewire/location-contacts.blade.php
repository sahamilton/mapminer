<div>
    <h2>{{$branch->branchname}} Contacts</h2>
        <div class="row mb4" style="padding-bottom: 10px">
        @include('livewire.partials._branchselector')
        @include('livewire.partials._search', ['search'=>"Search contacts"])   
        
    </div>

    <div class="row mb-4 ">
        @include('livewire.partials._perpage')
        <div wire:loading>
            <div class="spinner-border"></div>
        </div>
        <div class="col form-inline">
            <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-filter"></i>
            </span>

            <select wire:model="filter" 
            class="form-control">
                <option value="All">All</options>
                <option value='email'>with Email</options>
                <option value='contactphone'>with Phone</options>
                
                
            </select>
            <p style="margin-left:20px;" >
                <i  class="far fa-file-excel text-success"></i>
                <a href="{{route('contacts.export', [$branch->id, 'filter'=>$filter])}}">
                    <strong> Export Contacts</strong>
                </a>
            </p>
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
                    <a href="{{route('address.show', $contact->id)}}">
                        {{$contact->businessname}}
                    </a>
                </td>
                <td>{{$contact->city}}</td>
                <td>{{$contact->state}}</td>
                <td>
                    {{$contact->fullname ? $contact->fullname : $contact->firstname ." " . $contact->lastname}}
                </td>
                <td>{{$contact->title}}</td>
                <td>{{$contact->contactphone}}</td>
                <td>{{$contact->email}}</td>
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

