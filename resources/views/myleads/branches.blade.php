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





<livewire:lead-table :branch='$branch->id' :search='$search' />
@include('addresses.partials._deleteleadmodal') 

@include('branchleads.partials._branchcampaignmodal')

@include('partials._scripts')
@endsection
