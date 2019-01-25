@extends('site.layouts.default')
@section('content')
@if(isset($industry))
	<h1>{{$industry->filter}} Industry Sales Team</h1>
	<p><a href="{{ url()->previous() }}">Back to all sales org</a></p>
@else
	<h1>All Sales</h1>
@endif
@include('partials/_showsearchoptions')
@include('partials/advancedsearch')


<p><a href="{{route('person.map')}}"><i class="far fa-flag" aria-hidden="true"></i>Map View</a>


@if (auth()->user()->hasRole('admin'))
	<div class="float-right">
		<a href="{{{ route('users.create') }}}" class="btn btn-small btn-info iframe">

		<i class="fas fa-plus text-success" aria-hidden="true"></i>

		Create New Person</a>
	</div>
@endif
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Name</th>
		<th>Role</th>
		<th>Email</th>
		<th>Industry</th>
		<th>Servicelines</th>
	</thead>
	<tbody>
		@foreach($persons as $person)
			<tr>  
				<td>
					<a href="{{route('person.show',$person->id)}}">
					{{$person->fullName()}}
					</a>
				</td>
				<td>
					<ul>
						@foreach ($person->userdetails->roles as $role)
							{!! $role->display_name != 'User' ? "<li>" . $role->display_name ."</li>" : '' !!}

						@endforeach
					</ul>
				</td>
				<td>
					<a href="mailto:{{$person->userdetails->email}}" 
					title="Email {{$person->fullName()}}">
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
				<td>
					<ul>
						
					@foreach ($person->userdetails->serviceline as $serviceline)
					<li>{{$serviceline->ServiceLine}}</li>
					@endforeach
				</ul>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>

@include('partials/_scripts')

@endsection