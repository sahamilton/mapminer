@extends('site.layouts.default')
@section('content')

  
<div class="row float-right">
        <button type="button" 
            class="btn btn-info float-right" 
            data-toggle="modal" 
            data-target="#add_lead">
              Add Lead
        </button>
    </div>



    @include('branchleads.partials._mylead') 


    @livewire('lead-table', ['branch'=>$branch->id])
 
@include('branchleads.partials._branchleadmodal')
@include('branchleads.partials._branchcampaignmodal')
@include('partials._scripts')
@endsection
