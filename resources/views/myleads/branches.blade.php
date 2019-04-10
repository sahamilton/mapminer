@extends('site.layouts.default')
@section('content')

<h1>{{$title}}</h1>
<p><a href="{{route('dashboard.index')}}">Return To Branch Dashboard</a></p>
@if(count($myBranches)>1)
    <div class="col-sm-4">
        <form name="selectbranch" method="post" action="{{route('leads.branch')}}" >
        @csrf
            <select 
                class="form-control input-sm" 
                id="branchselect" 
                name="branch" 
                onchange="this.form.submit()">
                @foreach ($myBranches as $key=>$branchname)
                    <option {{$data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branchname}}</option>
                @endforeach 
            </select>

        </form>
    </div>
@endif
@php $branch = $data['branches']->first(); @endphp    
<div class="row float-right">
        <button type="button" 
            class="btn btn-info float-right" 
            data-toggle="modal" 
            data-target="#add_lead">
              Add Lead
        </button>
    </div>



@include('branchleads.partials._mylead') 


 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Company Name</th>
    <th>Address</th>
    <th>Remove</th>

    </thead>
    <tbody>

        @foreach($data['leads'] as $lead)
        
    <tr>
        <td>
            <a href="{{route('address.show',$lead->id)}}">
                {{ $lead->companyname != '' ? $lead->companyname: $lead->businessname}} 
            </a>
        </td>
        <td>{{$lead->businessname}}</td>
        <td>{{$lead->fullAddress()}}</td>
        <td>
           @if($lead->opportunities->count()==0) 
          <a 
            data-href="{{route('branch.lead.remove',$lead->id)}}" 
            data-toggle="modal" 
            data-target="#confirm-remove" 
            data-title = " this lead and all associated opportunities from your branch" 
            href="#">
                <i class="fas fa-trash-alt text-danger"></i>
            </a>
            @endif
        </td>




    </tr>
   @endforeach

    </tbody>
    </table>
 
@include('branchleads.partials._branchleadmodal')
@include('partials._scripts')
@endsection
