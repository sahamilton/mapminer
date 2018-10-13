
<script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="//cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<link href="{{asset('css/search.css')}}" rel='stylesheet' />
<form novalidate="novalidate" onsubmit="return false;" class="searchbox">
    <div role="search" class="searchbox__wrapper">
      <input id="search-input" type="search" name="search" placeholder="Search Sales Library" autocomplete="off" required="required" class="searchbox__input">
      <button type="submit" title="Submit your search query." class="searchbox__submit" >
      <svg role="img" aria-label="Search">

        <use xmlns:xlink="//www.w3.org/1999/xlink" xlink:href="#sbx-icon-search-13"></use>

          </svg>
        </button>
      <button type="reset" title="Clear the search query." class="searchbox__reset hide">
      <svg role="img" aria-label="Reset">

        <use xmlns:xlink="//www.w3.org/1999/xlink" xlink:href="#sbx-icon-clear-3"></use>

        </svg>
      </button>
    </div>
</form>







