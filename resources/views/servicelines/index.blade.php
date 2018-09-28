@extends('admin.layouts.default')
@section('content')

<h1>Service Lines</h1>
 @if (auth()->user()->hasRole('Admin'))
<div class="pull-right">
				<p><a href="{{{ route('serviceline.create') }}}" class="btn btn-small btn-info iframe">
<i class="fa fa-plus-circle " aria-hidden="true"></i>
 Create New Service Line</a></p>
			</div>
 @endif  

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     <th>ServiceLine</th>
     <th>Companies</th>
     <th>Branches</th>
     <th>Users</th>
     @if (auth()->user()->hasRole('Admin'))
     <th>Actions</th>
     @endif
       
    </thead>
    <tbody>
   @foreach($servicelines as $serviceline)

    <tr>  
	<td>{{$serviceline->ServiceLine}}</td>
	<td>
		@if(isset($serviceline->companyCount[0]))
				<a href ="{{route('serviceline.accounts',[$serviceline->id,'co'])}}"
				title = 'See all {{$serviceline->ServiceLine}} Companies '>
				{{$serviceline->companyCount[0]['aggregate']}}</a>
				
		@else
			0
		@endif
	</td>

	<td>
		@if(isset($serviceline->branchCount[0]))
			<a href ="{{route('serviceline.accounts',$serviceline->id)}}"
			title = 'See all {{$serviceline->ServiceLine}}  Branches '>
			{{$serviceline->branchCount[0]['aggregate']}}
			</a>

		@else
			0
		@endif
	</td>

	<td>
		@if(isset($serviceline->userCount[0]))
				<a href="{{route('serviceline.user',$serviceline->id)}}"
				 title = 'See all {{$serviceline->ServiceLine}}  users '>
				 {{$serviceline->userCount[0]['aggregate']}}</a>
		@else
			0
		@endif


	</td>

	
	@if(auth()->user()->hasRole('Admin'))
	<td>

            @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="{{route('serviceline.edit',$serviceline->id)}}/"><i class="fa fa-pencil" aria-hidden="true"> </i>
				Edit {{$serviceline->ServiceLine}}</a></li>
				<li><a data-href="{{route('serviceline.destroy',$serviceline->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$serviceline->ServiceLine}} and all its associations" href="#"><i class="fa fa-trash-o" aria-hidden="true"> </i> Delete {{$serviceline->ServiceLine}}</a></li>
			  </ul>
			</div>
		
	
    </td>
    @endif
      
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')
@endsection