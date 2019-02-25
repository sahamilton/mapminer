@extends('site/layouts/default')
@section('content')
<h2>{{$leads->first()->branch->branchname}} Leads </h2>
<div class="row float-right"><button type="button" 
    class="btn btn-info float-right" 
    data-toggle="modal" 
    data-target="#add_lead">
      Add Lead
</button>
</div>

@include('branches.partials._selector')
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Branch</th>
      <th>Company</th>

      <th>Address</th>
      <th>Lead Source</th>
      <th>Remove</th>
    </thead>
      <tbody>

 @foreach ($leads as $lead)   
        <tr> 
        <td><a href="{{route('branch.leads',$lead->branch->id)}}">{{$lead->branch->branchname}}</a></td>        
          <td>
            <a href="{{route('address.show',$lead->id)}}">
              {{$lead->address->businessname}}
            </a>
          </td>
          <td>{{$lead->address->fullAddress()}}</td>
          <td>
            @if($lead->address->leadsource)
              {{$lead->address->leadsource->source}}
            @endif
            @if($lead->createdBy)
             <em>by {{$lead->address->createdBy->person->fullName()}} </em>
            @endif
          </td>
          <td>
            
      <a 
        data-href="{{route('branch.lead.remove',$lead->address_id)}}" 
        data-toggle="modal" 
        data-target="#confirm-remove" 
        data-title = " this lead from your list" 
        href="#">
            <i class="fas fa-trash-alt text-danger"></i></a></td>
        </tr>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>

@include('partials._scripts')
@endsection
