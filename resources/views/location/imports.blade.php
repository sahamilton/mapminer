@extends('site.layouts.default')
@section('content')
<div class="container">
  <h2>{{$data['company']->companyname}}</h2>
 
  <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-link nav-item active" 
        id="add-tab" 
        data-toggle="tab" 
        href="#add" 
        role="tab" 
        aria-controls="add" 
        aria-selected="true">
      <strong> Add Locations ({{count($data['add'])}})</strong>
    </a>
    <a class="nav-link nav-item" 
        id="matched-tab" 
        data-toggle="tab" 
        href="#matched" 
        role="tab" 
        aria-controls="matched" 
        aria-selected="false">
      <strong> Matched Locations ({{count($data['matched'])}})</strong>
    </a>
<a class="nav-link nav-item" 
        id="delete-tab" 
        data-toggle="tab" 
        href="#delete" 
        role="tab" 
        aria-controls="delete" 
        aria-selected="false">
      <strong> Delete Locations ({{count($data['delete'])}})</strong>
    </a>
      
      </div>
    </nav>
    <form name="postprocess.create" method="post" 
    action ="{{route('postprocess.store')}}" >
      @csrf
    <div class="tab-content" id="myTabContent">
      <div id="add" class="tab-pane show active">
        <h4>Locations to Add</h4>
        @include('location.partials._add')
      </div>
      <div id="delete" class="tab-pane fade">
       <h4>Locations to Delete</h4>
       @include('location.partials._deleted')
      </div>
      <div id="matched" class="tab-pane fade">
        <h4>Matched Locations</h4>
        <p>Locations matched on geography will be updated if neccessary.</p>
      </div>
      </div>
      <input type="hidden" name="company_id" value="{{$data['company']->id}}" />
      <input type='submit' name="submit" value="Post changes" class="btn btn-info" />

    </form>
  </div>
@include('partials._scripts')
@endsection