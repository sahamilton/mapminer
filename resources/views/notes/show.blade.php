@extends('site/layouts/default')
@section('content')
<h2>My Location Notes</h2>

<p><a href="{{route('watch.index')}}" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Created</th>
		<th>Business Name</th>
		<th>Note</th>
	</thead>
	<tbody>
		@foreach($notes as $note)
		<tr>  
			<td>		
				{{date('d/m/Y',strtotime($note[$created_at]))}}
			</td>	

			<td>
			$title = "See details of the ".$note->relatesTo['businessname']." location";
				<a 
				title="See details of the {{$note->relatesTo['businessname']}} location" 
				href="{{route('locationshow',$note->relatesTo['id'])}}">
					{{$note->relatesTo['businessname']}}
				</a>
			</td>

			<td>
				{{$note['note']}}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@include('partials/_scripts')
@endsection
