@extends('admin.layouts.default')
@section('content')

<h4> Imported Data </h4>
<p><a href="{{route('imports.index')}}">Import New Data <i class="fas fa-file-upload"></i></a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    <th>Date</th>
    <th>Ref</th>
    <th>Type</th>
    <th>Imported By</th>
    <th>Count</th>
    <th>Description</th>
    <th>Actions</th>
  </thead>
  <tbody>
    @foreach ($imports as $import)
    <tr>
      <td>{{$import->created_at->format('Y-m-d')}}</td>
      <td><a href="{{route('fileimport.show',$import->id)}}">{{$import->ref}}</a></td>
      <td>{{$import->type}}</td>
      <td>{{$import->user->person->fullName()}}</td>
      <td>{{$import->addresses_count}}</td>
      <td>{{$import->description}}</td>
      <td><div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
      
        <a class="dropdown-item"
           data-href="{{route('fileimport.destroy',$import->id)}}" 
           data-toggle="modal" 
           data-target="#confirm-delete" 
           data-title = "this import and all its related addresses" 
           href="#"><i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete import
        </a>
        </ul>
      </div></td>
    </tr>
    @endforeach
</table>

@include('partials._modal')
@include('partials._scripts')

@endsection