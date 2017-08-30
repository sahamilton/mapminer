@extends('admin.layouts.default')

{{-- Page content --}}
@section('content')
<div class="container">
<h2>Project Sources</h2>
@can('manage_projects')
<div class="pull-right">
        <a href="{{{ route('projectsource.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Project Source</a>
      </div>
@endcan
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Source</th>
      <th>Source Ref</th>
      <th>Description</th>
      <th>Date From</th>
      <th>Date To</th>
      <th>Status</th>
      <th>Count</th>
    </thead>
    <tbody>

@foreach ($sources as $source)
<tr>
 <td>{{$source->source}}</td>
 <td>{{$source->reference}}</td>
 <td>{{$source->description}}</td>
 <td>{{$source->datefrom->format('m/d/Y')}}</td>
 <td>{{$source->dateto->format('m/d/Y')}}</td>
 <td>{{$source->status}}</td>
 <td>{{number_format(count($source->projects),0)}}</td>
 </tr>
@endforeach
</tbody>
</table>
</div>
@include('partials/_scripts')
@stop
        