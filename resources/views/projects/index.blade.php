@extends('site.layouts.maps')
@section('content')
<div id="message" style="color:#F00">{{\Session::get('message')}}</div>
    <style>
      #map_canvas {
        width: 800px;
        height: 600px;
      }
    </style>
    <h2>Search for Construction Projects</h2>
    <div style="margin-top:20px">
    @include('maps.partials._form')

   @include('partials.advancedsearch')
  
    <div id="map_canvas"></div>
</div>


@include('partials._maps')
@include('partials._newsscript')

@endsection