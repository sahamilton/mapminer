@extends('site.layouts.newmaps')
@section('content')
<div class="bh-sl-form-container">
        <form id="bh-sl-user-location" method="post" action="#">
            <div class="form-input">
              <label for="bh-sl-address">Enter Address or Zip Code:</label>
              <input type="text" id="bh-sl-address" name="bh-sl-address" />
            </div>

            <button id="bh-sl-submit" type="submit">Submit</button>
        </form>
      </div>

      <div id="bh-sl-map-container" class="bh-sl-map-container">
        <div id="bh-sl-map" class="bh-sl-map"></div>
        <div class="bh-sl-loc-list">
          <ul class="list"></ul>
        </div>
      </div>
    </div>

    <script>
	
	  $(function() {
		  $('#bh-sl-map-container').storeLocator({
            dataType: 'json',
            dataLocation: {{asset('data/locations.json')}}
        });
    </script>
@endsection


