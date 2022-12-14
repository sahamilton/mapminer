@extends('admin.layouts.default')

{{-- Page content --}}
@section('content')
<div class="container">
<h2>Project Sources</h2>
@can('manage_projects')
<div class="float-right">
        <a href="{{{ route('projectsource.create') }}}" class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Project Source</a>
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
      <th>Claimed</th>
      <th>Closed</th>
      <th>Avg Ranking</th>
      <th>Action</th>
    </thead>
    <tbody>

@foreach ($sources as $source)
<tr>
 <td><a href = "{{route('project.stats')."?id=".$source->id}}">{{$source->source}}</a></td>
 <td>{{$source->reference}}</td>
 <td>{{$source->description}}</td>
 <td>{{$source->datefrom->format('m/d/Y')}}</td>
 <td>{{$source->dateto->format('m/d/Y')}}</td>
 <td>{{$source->status}}</td>
 <td>{{number_format($stats[$source->id]['count'],0)}}</td>
 <td>{{$stats[$source->id]['statuses']['Claimed']}}</td>
 <td>{{$stats[$source->id]['statuses']['Closed']}}</td>
 <td>{{$stats[$source->id]['ranking']}}</td>
 <td class="col-md-2">
    

            <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
        

        <a class="dropdown-item"
        href="{{route('projectsource.edit',$source->id)}}">
          <i class="far fa-edit text-info"" aria-hidden="true"> </i>
          Edit {{$source->source}}
        </a>
        <a class="dropdown-item"
          data-href="{{route('projectsource.destroy',$source->id)}}" 
          data-toggle="modal" 
          data-target="#confirm-delete" 
          data-title = "{{$source->source}}" href="#">
          <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
          Delete {{$source->source}}
        </a>


        </ul>
      </div>
            </td>
 </tr>
@endforeach
</tbody>
</table>
</div>
@include('partials._modal')
@include('partials/_scripts')
@endsection
        