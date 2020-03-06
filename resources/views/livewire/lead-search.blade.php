
    <input wire:model='search' 
        type="text" 
        placeholder='Search Leads' />
<div>        
    <ul class="dropdown-menu show">
            @foreach ($leads as $lead)
                <li>
                    <a 
                    class="dropdown-item"
                    href="{{route('address.show', $lead->id)}}">{{$lead->businessname}}</a>
                </li>
            @endforeach
    </ul>
</div>
