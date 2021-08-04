<div>
    <h2>{{ucwords($status)}} Contacts Import</h2>
    <h4>{{$count}} Unique addresses</h4>
    <p><a href="{{route('contacts.importfile')}}">Start a new contacts import</a></p>
    <div class="row mb-4 ">
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            @include('livewire.partials._search', ['placeholder'=>'Search Contacts'])
        </div>
    </div>
    <div class="row mb-4 ">
        <div class="col form-inline">
            <i class="fas fa-filter text-danger"></i>
            <label for="status">Status&nbsp;</label>
            <select wire:model="status"  
                class="form-control">>
                    @foreach ($statuses as $key)
                    <option value={{$key}}>{{ucwords($key)}}</option>
                    @endforeach
                
            </select>
            <label for="Company">Company&nbsp;</label>
            <select wire:model="company"  
                class="form-control">>
               
                    <option value="All">All</option>
                    <option value='none'>No Company</option>
                    @foreach ($companies as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
            </select>
            @switch($status)
                @case('matched')
                <form name="importcontacts"
                    method='post'
                    action="{{route('contacts.importcontacts')}}">
                    @csrf</option>
                    <input type="submit" class='btn btn-success' value="Import Contacts" />
                </form>
                @break
                @case('unmatched')
                    <a href="{{route('contacts.createleads')}}" class='btn btn-success' >Create New Leads</a>
                @break
            @endswitch
            @if($company === 'none')
                <a href="{{route('contacts.createcompany')}}" class='btn btn-success' >Create New Companies</a>
            @endif
             
        </div>
    </div>
    <table>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                    Business Name
                    @include('includes._sort-icon', ['field' => 'businessname'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('street')" role="button" href="#">
                   Street
                    @include('includes._sort-icon', ['field' => 'street'])
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
                <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                   Contact
                    @include('includes._sort-icon', ['field' => 'lastname'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('title')" role="button" href="#">
                   Title
                    @include('includes._sort-icon', ['field' => 'title'])
                </a>
            </th>
            @if($status == 'assigned')
                <th>
                    Assigned To Branch
                </th>
            @endif
            
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
            <tr>
                <td>
                    @if($contact->address_id)
                    <a href="{{route('address.show', $contact->address_id)}}" target='_blank'>
                        {{$contact->businessname}}
                    </a>
                    @else
                    {{$contact->businessname}}
                    @endif
                </td>
                <td>{{$contact->street}}</td>
                <td>{{$contact->city}}</td>
                <td>{{$contact->state}}</td>
                <td>{{$contact->fullName()}}</td>
                <td>{{$contact->title}}</td>
                @if($status == 'assigned')
                <td>
                    
                    @foreach ($contact->address->assignedToBranch as $branch)
                        {{$branch->branchname}}
                        {{! $loop->last ? ", " : ''}}
                    @endforeach
                </td>
                @endif
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
