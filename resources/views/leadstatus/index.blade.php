@extends('admin.layouts.default')
@section('content')

<h1>Leads</h1>


@if (Auth::user()->hasRole('Admin'))

<div class="pull-right">
        <a href="{{{ route('leadstatus.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Lead Status</a>
      </div>
@endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>
   
    </th>
    @if (Auth::user()->hasRole('Admin'))
    <th>Actions</th>
    @endif
   
       
    </thead>
    <tbody>
   @foreach($leadstatuses as $status)
    
    
    <tr>  
  @if (Auth::user()->hasRole('Admin'))
    <td>
            @include('partials._modal')
    
         <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
        
        <li><a href="{{route('leadstatus.edit',$status->id}}"><i class="glyphicon glyphicon-pencil"></i> Edit this lead status</a></li>
        <li><a data-href="{{'leadstatus.destroy',$source->id}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this lead status " href="#"><i class="glyphicon glyphicon-trash"></i> Delete this lead status</a></li>
        </ul>
      </div>
    
    
    </td>
   @endif
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')
@stop