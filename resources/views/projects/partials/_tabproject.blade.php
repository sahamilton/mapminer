<p><strong>Address:</strong>
<blockquote>{{$project->project_addr1}} /{{$project->project_addr2}}<br />{{$project->project_city}}, {{$project->project_state}} 
{{$project->project_zipcode}}
<br /><em>(Map accuracy: {{$project->accuracy}})</em>
</blockquote>
<p><strong>People Ready Status:</strong>
@can('manage_projects')
  @include('projects.partials._manageprojects')
@else
@if(count($project->owner)>0)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}
  @else
    Open
@endif
@endcan
</p>
<div id="map-container">
  <div style="float:left;width:300px">

  <p><strong>Type:</strong>

  <p><strong>Dodge ref #:</strong>{{$project->dodge_repnum}}</p>

  <p><strong>Category:</strong>
  {{$project->structure_header}} / {{$project->project_type}}</p>
  <p><strong>Stage:</strong>{{$project->stage}}</p>
  <p><strong>Ownership:</strong>{{$project->ownership}}</p>
  <p><strong>Bid Date:</strong>{{$project->bid_date}}</p>
  <p><strong>Project Start:</strong>{{$project->start_yearmo}}</p>
  <p><strong>Target Start:</strong>{{$project->target_start_date}}</p>
  <p><strong>Target Completion:</strong>{{$project->target_comp_date}}</p>
  <p><strong>Work type:</strong>{{$project->work_type}}</p>
  <p><strong>Project Status:</strong>{{$project->status}}</p>

  <p><strong>Total Project Value:</strong>{{$project->total_project_value}}k</p>
</div>

<div id="map" style="height:300px;width:500px;border:red solid 1px">
@if(! $project->lat)
     	Unable to geocode this address
     @endif

</div> 
</div>
@include('projects.partials._companylist')

