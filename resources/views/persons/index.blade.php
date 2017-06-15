@extends('site/layouts/default')
@section('content')
@if(isset($industry))
	<h1>{{$industry->filter}} Industry Sales Team</h1>
	<p><a href="{{ url()->previous() }}">Back to all sales org</a></p>
@else
	<h1>All Sales</h1>
@endif
@include('partials/_showsearchoptions')
@include('partials/advancedsearch')

<p><a href="{{URL::to('people/map')}}"><i class="glyphicon glyphicon-flag"> </i>Map View</a>

@if (auth()->user()->hasRole('Admin'))
	<div class="pull-right">
		<a href="{{{ URL::to('admin/users/create') }}}" class="btn btn-small btn-info iframe">
		<span class="glyphicon glyphicon-plus-sign"> </span> 
		Create New Person</a>
	</div>
@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Name</th>
		<th>Role</th>
		<th>Email</th>
		<th>Industry</th>
	</thead>
	<tbody>
		@foreach($persons as $person)
			<tr>  
				<td>
					<a href="{{route('person.show',$person->id)}}">
					{{$person->postName()}}
					</a>
				</td>
				<td>
					<ul>
						@foreach ($person->userdetails->roles as $role)
							{!! $role->name != 'User' ? "<li>" . $role->name ."</li>" : '' !!}

						@endforeach
					</ul>
				</td>
				<td>
					<a href="mailto:{{$person->userdetails->email}}" 
					title="Email {{$person->postName()}}">
						{{$person->userdetails->email}}
					</a>
				</td>
				<td>
					<ul>
					@foreach ($person->industryfocus as $industry)
						@if(strtolower($industry->filter) == 'not specified')
							<li><a href="{{route('person.vertical',$industry->id)}}">General</a></li>
						@else
							<li><a href="{{route('person.vertical',$industry->id)}}">{{$industry->filter}}</a></li>
						@endif


					@endforeach

					</ul>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>

@include('partials/_scripts')

@endsection