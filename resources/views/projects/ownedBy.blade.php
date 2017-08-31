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
    <td>{{$project->project_addr1}}</td>
    <td>{{$project->project_city}}</td>
    <td>{{$project->project_state}},{{$project->project_zipcode}}</td>
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
    <td><a href="{{route('project.release',$project->id)}}">Release</a></td>
    </tr>
  @endforeach

  </tbody>
</table>
  

</div>
@include('partials._scripts')
@stop
