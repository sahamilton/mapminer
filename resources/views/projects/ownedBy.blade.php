@extends('admin.layouts.default')
@section('content')

<div class="container">

<h2>{{$owner->postName()}}'s Construction Projects </h2>

<p><a href="{{route('project.stats')}}">Return to Projects Summary</a></p>
  <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    
    <th>Project</th>
    <th>Address</th>
    <th>City</th>
    <th>State</th>
    <th>Type</th>
    <th>Ownership</th>
    <th>Stage</th>
    <th>PR Status</th>
    
    <th>Total Value ($k)</th>
    <th>Rating</th>
    <td>Actions</td>

  </thead>
  <tbody>
  @foreach($projects as $project)

    <tr>  
    <td><a href="{{route('projects.show',$project->id)}}"
    title="See details of this project">{{$project->project_title}}</a></td>
    <td>{{$project->street}}</td>
    <td>{{$project->city}}</td>
    <td>{{$project->state}},{{$project->zipcode}}</td>
    <td>{{$project->structure_header}} / {{$project->project_type}}</td>
    <td>{{$project->ownership}}</td>
    <td>{{$project->stage}}</td>
    <td>
    
    {{$project->owner[0]->pivot->status}}
    
    </td>
    <td style="text-align:right">{{$project->total_project_value}}</td>
    <td style="text-align:right">
    
    {{number_format($project->owner[0]->pivot->ranking,1)}}
    
    </td>
    <td><a data-href="{{route('projects.release',$project->id)}}" 
        data-toggle="modal" 
        data-target="#confirm-delete" 
        data-title = "{{$project->project_title}}" href="#">
        <i class="fa fa-trash-o" aria-hidden="true"> </i> 
        Release Project</a></a></td>
    </tr>
  @endforeach

  </tbody>
</table>
  

</div>
@include('projects.partials._release')
@include('partials._scripts')
@stop
