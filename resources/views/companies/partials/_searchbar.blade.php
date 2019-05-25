
    <div class="col-sm-6 col-sm-offset-3">
        <div id="imaginary_container"> 
            <div class="input-group stylish-input-group">
                <input type="text" 
                class="form-control" 
                    id="companysearch" 
                    name="companyname" 
                    placeholder="Search Companies" 
                    autocomplete="on" >
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-search"></i>
                    </button>
            </div>
        </div>
    </div>



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
            
            $('#companysearch').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'companies',
                source: bloodhound,
                display: function(data) {

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
                        var url = '{{ route("address.show", ":slug") }}';
                        var color = 'orange';
                        url = url.replace(':slug', data.id,color);
                    return '<div font-weight:bold; margin-top:-10px ! important;" class="list-group-item"><a style="color:' + color +'" href="'+ url +'">'
                         + data.businessname + ', ' + data.city +   '</a></div></div>'
                    }
                }
            });
        });
    </script>