@extends('site.layouts.mobile')
@section('content')
@php
$distances = [1=>'1 mile',2=>'2 miles',5=>'5 miles', 10=>'10 miles',25=>'25 miles'];
@endphp
<div class="container">
<h2><a href="{{route('mobile.index')}}">Mobile View</a></h2>
<h4>{{$branch->branchname}}</h4>

    <div class="col-md-5">
      
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
        <div id="pac-container" class="form-group mx-sm-3 mb-2 search-container">
            
            <label for type>Address</label>
            <input 
            id="pac-input" 
            class="form-control {{ $errors->has('search') ? ' has-error' : ''}}" 
            type="text" 
            name="search" 
            
            value="{{$address}}"
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
  <img  id="place-icon" src="" width="16" height="16" >
  <a id="place-link" href=""></a>

</div>


<div id="message" style="color:#F00">{{\Session::get('message')}}</div>

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