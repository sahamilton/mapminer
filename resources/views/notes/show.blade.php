@extends('site/layouts/default')
@section('content')


<p><a href="{{route('watch.index')}}" title="Review my watch list"><i class="fas fa-th-list" aria-hidden="true"></i> View My Watch List</a></p>

@foreach ($types as $key=>$type)
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h2>My {{ucwords($type)}} Notes</h2>
		<table id ='sorttable{{$key}}' class='table table-striped table-bordered table-condensed table-hover'>
			<thead>
				<th>Created</th>
				<th>Business Name</th>
				<th>Note</th>
			</thead>
			<tbody>
				@foreach($notes[$type] as $note)
				<tr>  
					<td>		
						{{$note->created_at->format('Y-m-d')}}
					</td>	

					<td>

						@if($type=='location' && $note->relatesToLocation && $note->relatesToLocation->count()>0)

							<a href="{{route('locations.show',$note->relatesToLocation->id)}}"
							title="See details of location">
							{{$note->relatesToLocation->businessname}}
						</a>
						@elseif($type=='lead' && $note->relatesToProspect->count()>0)
							<a href="{{route('projects.show',$note->relatesToProspect->id)}}"
							title="See details of prospect">
							{{$note->relatesToProspect->businessname}}
						</a>
						@elseif($type=='project' && $note->relatesToProject->count()>0)
							<a href="{{route('projects.show',$note->relatesToProject->id)}}"
							title="See details of project">
							{{$note->relatesToProject->project_title}}

							</a>
						@endif
					</td>
					<td>
						{{$note->note}}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@endforeach
@include('partials/_scripts')
@endsection
