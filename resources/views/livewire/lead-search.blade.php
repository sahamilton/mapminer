<div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/bloodhound.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/typeahead.jquery.min.js"></script>
       

    <div><input type="text" id = 'search' name="search"></div>

    @if($address) 

        @foreach ($fields as $field)
            @if($loop->first)
                <strong>{{ucwords($field)}}:</strong><a href="{{route('address.show', $address->id)}}">{{$address->$field}}</a><br/>
            @else
                <strong>{{ucwords($field)}}:</strong>{{$address->$field}}<br/>
            @endif
        @endforeach
         <button class="btn btn-info" href="#" wire:click.prevent="addActivity({{ $address->id }})">
                <i class="fa-solid fa-calendar-lines-pen"></i>
                Add Activity
            </button>   
        @include('activities.partials._modal')
    @endif
  
    <script>
        document.addEventListener('livewire:load', function () {
            var bloodhound = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/myleads/find?q=%QUERY%',
                    wildcard: '%QUERY%'
                },
            });
            
            $('#search').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'leads',
                source: bloodhound,
                display: function(data) {
                    @this.lead_id = data.id
                    return data.businessname +" "+ data.city //Input value to be set when you select a suggestion. 
                },
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                    ],
                    header: [
                        '<div class="list-group search-results-dropdown">'
                    ],
                    suggestion: function(data) {
                    
                        
                    return '<div style="font-weight:bold; margin-top:-10px ! important;" class="list-group-item"><a style="color:orange" href="#" >'
                         + data.businessname + ', ' + data.city +   '</a></div>'
                    }
                }
            });
        });
</script>


</div>
