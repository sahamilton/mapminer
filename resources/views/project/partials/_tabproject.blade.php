<p><strong>Address:</strong>
<blockquote>{{$location->project->street}} /{{$location->project->address2}}<br />{{$location->project->city}}, {{$location->project->state}} 
{{$location->project->zip}}
<br /><em>(Map accuracy: {{$location->project->accuracy}})</em>
</blockquote>
<p><strong>People Ready Status:</strong>
@can('manage_projects')
  @include('projects.partials._manageprojects')
@else
@if($location->project->owner->count()>0)
    {{$location->project->owner[0]->pivot->status}} by {{$location->project->owner[0]->postName()}}
  @else
    Open
@endif
@endcan
</p>
<div id="map-container">
  <div style="float:left;width:300px">

  <p><strong>Type:</strong>

  <p><strong>Dodge ref #:</strong>{{$location->project->dodge_repnum}}</p>

  <p><strong>Category:</strong>
  {{$location->project->structure_header}} / {{$location->project->project_type}}</p>
  <p><strong>Stage:</strong>{{$location->project->stage}}</p>
  <p><strong>Ownership:</strong>{{$location->project->ownership}}</p>
  <p><strong>Bid Date:</strong>{{$location->project->bid_date}}</p>
  <p><strong>Project Start:</strong>{{$location->project->start_yearmo}}</p>
  <p><strong>Target Start:</strong>{{$location->project->target_start_date}}</p>
  <p><strong>Target Completion:</strong>{{$location->project->target_comp_date}}</p>
  <p><strong>Work type:</strong>{{$location->project->work_type}}</p>
  <p><strong>Project Status:</strong>{{$location->project->status}}</p>

  <p><strong>Total Project Value:</strong>{{$location->project->total_project_value}}k</p>
</div>

<div id="map" style="height:300px;width:500px;border:red solid 1px">
@if(! $location->project->lat)
     	Unable to geocode this address
     @endif

</div> 
</div>
@include('projects.partials._companylist')

