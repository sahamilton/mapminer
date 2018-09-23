@extends('admin.layouts.default')
@section('content')

<h1>Prospect Statuses</h1>


@if (auth()->user()->hasRole('Admin'))

<div class="pull-right">
        <a href="{{ route('leadstatus.create') }}" class="btn btn-small btn-info iframe">
<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
 Create New Prospect Status</a>
      </div>
@endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Status</th>
    <th>Average Ranking</th>
    <th>Number</th>
    @if (auth()->user()->hasRole('Admin'))
    <th>Actions</th>
    @endif
   
       
    </thead>
    <tbody>
   @foreach($leadstatuses as $status)
    
    
    <tr>  
    <td>
        <a href= "{{route('leadstatus.show',$status->id)}}">
        {{$status->sequence}}-{{$status->status}}
        </a>
    </td>
    <td>
        @if($status->leads->count()>0)
        {{number_format($status->leads->sum('pivot.rating') / $status->leads->count(),2)}}
        @endif
    </td>
    <td>{{$status->leads->count()}}
  @if (auth()->user()->hasRole('Admin'))
    <td>
            @include('partials._modal')
    
         <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
        
        <li><a href="{{route('leadstatus.edit',$status->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit this lead status</a></li>
        <li><a data-href="{{route('leadstatus.destroy',$status->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this lead status " href="#"><i class="fa fa-trash-o" aria-hidden="true"> </i> Delete this lead status</a></li>
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