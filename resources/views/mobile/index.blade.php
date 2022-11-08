@extends('site.layouts.mobile')
@section('content')
@php
$distances = [1=>'1 mile',2=>'2 miles',5=>'5 miles', 10=>'10 miles',25=>'25 miles'];

@endphp

<div class="container" style="margin-bottom:40px">
    <div class="col-md-5">
        <h2><a href="{{route('mobile.index')}}">Branch View</a></h2>
        <h4>{{$branch->branchname}}</h4>

    
      
    <form action="{{route('mobile.search')}}" method = 'post' name="mapselector">
        @csrf
      
        @if($branches->count() > 1)
            @include('mobile.partials._branchselector')
        
        @endif
        <div class="form-group mx-sm-3 mb-2">
            <label for type>Select</label>
            <select  onchange="this.form.submit()" class="form-control" name="type">
                

                <option 
                    value="activities"
                    @if(isset($type) && $type=='activities') selected @endif
                >
                    Open Activities ({{$branch->openactivities->count()}})
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
                @foreach ($distances as $key=>$dist)
                <option 
                    value='{{$key}}'
                    @if(isset($distance) && $distance==$key) selected @endif
                >
                    {{$dist}}
                </option>
                @endforeach
                

            </select>
        </div>
       
       <div id="pac-container" class="input-group mx-sm-3 mb-2">
          
          <input 
              id="pac-input"
              type="text" 
              class="form-control  {{ $errors->has('search') ? ' has-error' : ''}}" 
              placeholder="Enter address or check Help Support for auto geocoding" 
              aria-label="Search term" 
              value="{{$searchaddress}}"
              name="search"
              aria-describedby="basic-addon">
          <div class="input-group-append">
            <button class="btn btn-success" type="submit">Search!</button>
          </div>
    </form>


<div id="map" style="max-width:100%;max-height:100%; "></div>
<div id="infowindow-content">
  <img  id="place-icon" src="" width="16" height="16" >
  <a id="place-link" href=""></a>

</div>


<div id="message" style="color:#F00">{{\Session::get('message')}}</div>

    <div style="margin-top:20px"></div>

<div style="clear:both"></div>

@if(isset($results))
    @if($type=='activities')
        <h4>Open Activities within {{$distance}} miles</h4>
        @include('mobile.partials._activities')
        
    @elseif ($type== 'leads')
    <h4>Open Leads within {{$distance}} miles</h4>
        @include('mobile.partials._leads')
        @include('mobile.partials._activitiesmodal')
    @elseif ($type == 'opportunities')
    <h4>Open Oportunities within {{$distance}} miles</h4>
        @include('mobile.partials._opportunities')
        @include('mobile.partials._activitiesmodal')
    @else
      
    @endif

@endif
</div>
</div>

@include('mobile.partials._mapscript')
@include('partials._maps')
@include('partials._scripts')
@endsection