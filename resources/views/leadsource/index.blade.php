@extends('admin/layouts/default')
@section('content')

<h1>Lead Sources</h1>


@if (Auth::user()->hasRole('Admin'))

<div class="pull-right">
				<a href="{{{ route('leadsource.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Lead Source</a>
			</div>
@endif

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Lead Source</th>
    <th>Description</th>
    <th>Reference</th>
    <th>Leads</th>
    <th>Available From / To</th>

    @if (Auth::user()->hasRole('Admin'))
    <th>Actions</th>
    @endif
   
       
    </thead>
    <tbody>
   @foreach($leadsources as $source)
    <tr> 
   	<td>{{$source->source}}</td>
    <td>{{$source->description}}</td>
    <td>{{$source->reference}}</td>
    <td>{{count($source->leads)}}</td>
   	<td>
    @if($source->dateto < Carbon\Carbon::now())
    Expired {{$source->datefrom->format('M j,Y')}}
    @elseif ($source->datefrom > Carbon\Carbon::now())
        Commences {{$source->datefrom->format('M j,Y')}}
    @else
        {{$source->datefrom->format('M j,Y')}} - {{$source->dateto->format('M j,Y')}}
    @endif
    </td>
    
	@if (Auth::user()->hasRole('Admin'))
    <td>
            @include('partials/_modal')
    
         <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="{{route('leadsource.edit',$source->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit this lead source</a></li>
				<li><a data-href="{{route('leadsource.purge',$source->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this lead source and all its leads" href="#"><i class="glyphicon glyphicon-trash"></i> Delete this lead source</a></li>
			  </ul>
			</div>
		
		
    </td>
   @endif
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')
@stop