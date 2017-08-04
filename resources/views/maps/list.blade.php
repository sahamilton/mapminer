@extends('site.layouts.default')


@section('content')

<h1>{{$data['title']}}</h1>

{!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
<p><a href="{{route('watch.index')}}" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>
<p><a href="{{route('watch.export')}}" title="Download my watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Watch List</a> </p>

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
