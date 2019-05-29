@extends('site.layouts.default')


@section('content')

<h1>{{$data['title']}}</h1>

@if ($data['type']== 'projects')

<p><a href="{{route('projects.myprojects')}}" title="Review my claimed projects"><i class="fas fa-th-list" aria-hidden="true"></i> View My Projects</a></p>
<p><a href="{{route('projects.export')}}" title="Download my claimed projects as a CSV / Excel file"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download My Projects</a> </p>
@else
{!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}


@endif

@include('maps.partials._form')


@include('partials.advancedsearch')

@if($data['type']=='location')
	@include('maps.accountlist')
@elseif($data['type']=='branch')
	@include('maps.branchlist')
@elseif($data['type'] =='projects')
	@include('projects.projectlist')
@elseif($data['type']=='people')
	Boo!
@endif

 
   
@include('partials/_scripts')

@endsection
