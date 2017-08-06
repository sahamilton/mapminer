@extends('site.layouts.default')


@section('content')

<h1>{{$data['title']}}</h1>
@if ($data['type']== 'projects')
<p><a href="{{route('projects.myprojects')}}" title="Review my claimed projects"><i class="glyphicon glyphicon-th-list"></i> View My Projects</a></p>
<p><a href="{{route('projects.export')}}" title="Download my claimed projects as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Projects</a> </p>
@else
{!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
<p><a href="{{route('watch.index')}}" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>
<p><a href="{{route('watch.export')}}" title="Download my watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Watch List</a> </p>
@endif
@include('maps/partials/_form')
@include('partials.advancedsearch')

@if($data['type']=='branch')
	@include('maps.branchlist')
@elseif($data['type'] =='projects')
	@include('projects.projectlist')
@else
    @include('maps.accountlist')
@endif    
@include('partials/_scripts')

@stop
