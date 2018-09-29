@extends('admin.layouts.default')
@section('content')

<h1>Web Leads</h1>
 @if (auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations'))
<div class="pull-right">
				<p><a href="{{{ route('webleads.import.create') }}}" class="btn btn-small btn-info iframe">
<i class="far fa-plus-circle " aria-hidden="true"></i>
 Import New Web Lead</a></p>
			</div>
 @endif  

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Date Added</th>
     <th>Company</th>
     <th>Address</th>
     <th>Rating</th>
     <th>Status</th>
     <th>Industry</th>
     <th>Actions</th>
   
       
    </thead>
    <tbody>
   @foreach($webleads as $lead)

    <tr> 
    <td>{{$lead->created_at->format('Y-m-d')}}</td> 
	<td><a href="{{route('webleads.show',$lead->id)}}">{{$lead->company_name}}</a></td>
	<td>{{$lead->city}}, {{$lead->state}}</td>
	
	<td>{{$lead->rating}}</td>
	<td>
		@if($lead->salesteam->count()>0)
			Assigned to{{$lead->salesteam->first()->postName()}} on 
						{{$lead->salesteam->first()->pivot->created_at->format('j M, Y')}}
		@else
			Open
		@endif
	<td>{{$lead->industry}}</td>
	

	<td>

      
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<a class="dropdown-item"
				href="{{route('webleads.edit',$lead->id)}}/">
					<i class="far fa-edit text-info"" aria-hidden="true"> </i>
				Edit Web Lead</a>
				<a class="dropdown-item"
				 data-href="{{route('webleads.destroy',$lead->id)}}" 
					data-toggle="modal" 
					data-target="#confirm-delete" 
					data-title = "This web lead and all its associations" href="#">
					<i class="far fa-trash-o text-danger" aria-hidden="true"> </i> 
				Delete Web Lead</a>
			  </ul>
			</div>
		
	
    </td>
 
    </tr>
   @endforeach
    
    </tbody>
    </table>
          @include('partials/_modal')
@include('partials/_scripts')
@endsection