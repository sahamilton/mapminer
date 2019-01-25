@extends('site/layouts/default')
@section('content')

<h2> Audience for {{$news->title}}</h2>
<h4>Available from {{$news->datefrom->format('M jS, Y')}} to {{$news->dateto->format('M jS, Y')}}</h4>
<p><a href="{{route('news.index')}}">Return to all news</a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Name</th>
		<th>Role</th>
		<th>Email</th>
		<th>Industry</th>
	</thead>
	<tbody>
		@foreach($audience as $user)
			<tr>  
				<td>
					
					{{$user->person->fullName()}}

				</td>
				<td>
					<ul>
						@foreach ($user->roles as $role)
							{!! $role->display_name != 'User' ? "<li>" . $role->display_name ."</li>" : '' !!}

						@endforeach
					</ul>
				</td>
				<td>
					
						{{$user->email}}
					</a>
				</td>
				<td>
					<ul>
					@foreach ($user->person->industryfocus as $industry)
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

@include('partials._scripts')

@endsection