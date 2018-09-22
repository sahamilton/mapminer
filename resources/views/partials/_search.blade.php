  <script>
  var client = algoliasearch("GMKQ3OLFQU", "182aa25d6b42e13fc0574f6035455b26")
  var index = client.initIndex('documents');
  var myAutocomplete = autocomplete('#search-input', {hint: false}, [
    {
      source: autocomplete.sources.hits(index, {hitsPerPage: 5}),
      displayKey: 'title',
      templates: {
        suggestion: function(suggestion) {

        var urlRef = '{{ route("documents.show", ":id") }}';
        urlRef = urlRef.replace(':id', suggestion.id);
        var sugTemplate =  "<a href=" + urlRef + "><span>"+ suggestion._highlightResult.title.value +
        " / ("+ suggestion._highlightResult.doctype.value +")</span></a>";
          return sugTemplate;
        }
      }
    }
  ]).on('autocomplete:selected', function(event, suggestion, dataset) {
    console.log(suggestion, dataset);
  });

document.querySelector(".searchbox [type='reset']").addEventListener("click", function() {
  document.querySelector(".aa-input").focus();
  this.classList.add("hide");
  myAutocomplete.autocomplete.setVal("");
});

document.querySelector("#search-input").addEventListener("keyup", function() {
  var searchbox = document.querySelector(".aa-input");
  var reset = document.querySelector(".searchbox [type='reset']");
  if (searchbox.value.length === 0){
    reset.classList.add("hide");
  } else {
    reset.classList.remove('hide');
  }
});</script>