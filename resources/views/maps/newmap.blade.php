@extends('site.layouts.newmaps')
@section('content')
<div class="bh-sl-container">
        <div class="jumbotron jumbotron-fluid">
          <div class="container">
            <hr class="my-4">

            <div class="bh-sl-form-container">
              <form id="bh-sl-user-location" class="form-inline" method="post" action="#" role="form">
                <div class="form-input form-group">
                  <label for="bh-sl-address">Enter Address or Zip Code:</label>
                  <input class="form-control" type="text" id="bh-sl-address" name="bh-sl-address" />
                </div>

                <button id="bh-sl-submit" class="btn btn-primary" type="submit" role="button">Submit</button>
              </form>
            </div>
          </div>
        </div>
        
        <div id="bh-sl-map-container" class="bh-sl-map-container">
          <div class="container-fluid">
            <div id="map-results-container" class="row">
              <div id="bh-sl-map" class="bh-sl-map col-md-9"></div>
              <div class="bh-sl-loc-list col-md-3">
                <ul class="list list-unstyled"></ul>
              </div>
            </div>
          </div>
        </div>
      </div>

    
@endsection


