@extends('site.layouts.mobile')
@section('content')
<style>
    .search-container {
        float: none;
    }
    .topnav a, .topnav input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
</style>
<div class="container">
<h2><a href="{{route('mobile.index')}}">Mobile View</a></h2>
<h4>{{$branch->branchname}}</h4>
    <div class="col-md-5">
    <form action="{{route('mobile.search')}}" method = 'post' name="mapselector">
        @csrf
        <div class="form-group mx-sm-3 mb-2">
            <label for type>Select</label>
            <select  onchange="this.form.submit()" class="form-control" name="type">
                

                <option 
                    value="activities"
                    @if(isset($type) && $type=='activities') selected @endif
                >
                    Open Activities ({{$branch->openactivities}})
                </option>
                
                <option 
                    value="leads" 
                    @if(isset($type) && $type=='leads') selected @endif
                >
                    Open Leads ({{$branch->leads_count}})
                </option>


                <option 
                    value="opportunities"
                    @if(isset($type) && $type=='opportunities') selected @endif
                >
                    Open Opportunities ({{$branch->open}})
                </option>
                
            </select>
        </div>
        <div class="form-group mx-sm-3 mb-2">
            <label for distance>Within</label>
            <select onchange="this.form.submit()" class="form-control" name="distance">
                <option 
                    value='1'
                    @if(isset($distance) && $distance==1) selected @endif
                >
                    1 mile
                </option>
                <option 
                    value='2'
                    @if(isset($distance) && $distance==2) selected @endif
                >
                    2 miles
                </option>
                <option 
                    value='5'
                    @if(isset($distance) && $distance==5) selected @endif
                >
                    5 miles
                </option>
                <option 
                    value='10'
                    @if(isset($distance) && $distance==10) selected @endif
                >
                    10 miles
                </option>
                <option 
                    value='25'
                    @if(isset($distance) && $distance==25) selected @endif
                >
                    25 Miles
                </option>

            </select>
        </div>
        <div id="pac-container" class="form-group mx-sm-3 mb-2 search-container">
            
            <label for type>Address</label>
            <input 
            id="pac-input" 
            class="form-control {{ $errors->has('search') ? ' has-error' : ''}}" 
            type="text" 
            name="search" 
            
            value="{{session()->has('geo.address') ? session('geo.address') : ''}}"
            id="search"
            required
           
            placeholder='Enter address or check Help Support for auto geocoding' />
            <button type="submit" class= "btn btn-success">

            <i class="fas fa-search" aria-hidden="true"></i> </button>
        </div>
    </form>
</div>

<div id="map"></div>
<div id="infowindow-content">
  <img src="" width="16" height="16" id="place-icon">
  <span id="place-name" class="title"></span><br>
  <span id="place-address"></span>
</div>


<div id="message" style="color:#F00">{{\Session::get('message')}}</div>
    <style>
      #map_canvas {
        width: 800px;
        height: 600px;
      
      }
    </style>
    <div style="margin-top:20px"></div>
</div>
<div style="clear:both"></div>
@if(isset($results))
    @if($type=='activities')
        @include('mobile.partials._activities')
    
    @elseif ($type== 'leads')
        @include('mobile.partials._leads')
    @elseif ($type == 'opportunities')
        @include('mobile.partials._opportunities')
    @else
        
    @endif

@endif

@include('mobile.partials._mapscript')
@include('partials._maps')
@include('partials._scripts')
@endsection