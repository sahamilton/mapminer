@extends('site.layouts.default')
@section('content')


@if(count($myBranches) > 1)
    <div class="col-sm-4">
        <form name="selectbranch" method="post" action="{{route('leads.branch')}}" >
        @csrf
            <select 
                class="form-control input-sm" 
                id="branchselect" 
                name="branch" 
                onchange="this.form.submit()">
                @foreach ($myBranches as $key=>$branchname)
                    <option {{$branch->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branchname}}</option>
                @endforeach 
            </select>

        </form>
    </div>
@endif
   
    <div class="row float-right">
        <button type="button" 
            class="btn btn-info float-right" 
            data-toggle="modal" 
            data-target="#add_lead">
              Add Lead!
        </button>
    </div>



    @include('branchleads.partials._mylead') 


    @livewire('lead-table', ['branch_id'=>$branch->id])
@include('addresses.partials._deleteleadmodal') 
@include('branchleads.partials._branchleadmodal')
@include('branchleads.partials._branchcampaignmodal')

@include('partials._scripts')
@endsection
