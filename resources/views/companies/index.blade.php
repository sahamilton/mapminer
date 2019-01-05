@extends('site/layouts/default')
@section('content')

<h1>{{$title}}</h1>

<input  type="text" 
            required id="search" 
            name="companyname" 
            placeholder="Type to search companies" 
            autocomplete="on" ><i class="fas fa-search"></i>
    <script>
        $(document).ready(function() {
            var bloodhound = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/company/find?q=%QUERY%',
                    wildcard: '%QUERY%'
                },
            });
            
            $('#search').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'companies',
                source: bloodhound,
                display: function(data) {
                    return data.companyname //Input value to be set when you select a suggestion. 
                },
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                    ],
                    header: [
                        '<div class="list-group search-results-dropdown">'
                    ],
                    suggestion: function(data) {
                        var url = '{{ route("company.show", ":slug") }}';

                        url = url.replace(':slug', data.id);
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item"><a href="'+url+'">'
                         + data.companyname + '</a></div></div>'
                    }
                }
            });
        });
    </script>
@include('partials/_modal')
@include('partials/_scripts')
@endsection
