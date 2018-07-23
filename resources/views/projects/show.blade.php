@extends('site.layouts.default')
@section('content')

<div class="container">

<h2>Construction Project</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a> | <a href="{{route('projects.myprojects')}}">Return to my projects</a></p>

@if($project->owned()  or auth()->user()->hasRole('Admin'))
  <h4><p><strong>Project Title:</strong><a href="#" 
  id="project_title" 
  data-type="text" 
  data-pk="{{$project->id}}" 
  data-title="Update Project Title" 
  class="editable editable-click editable-open" 
  data-original-title="" 
  title="">{{$project->project_title}}</a></h4>
@else
 <h4><p><strong>Project Title:</strong>{{$project->project_title}}</h4>
@endif

<p><strong>Address:</strong>

<blockquote>{{$project->street}} /{{$project->addr2}}<br />{{$project->city}}, {{$project->state}} 
{{$project->zip}}
</blockquote>
<div class="row">
<p><strong>People Ready Status:</strong>

@can('manage_projects')
  @include('projects.partials._manageprojects')
@else
@if(count($project->owner)>0)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}</p>
  @else
    Open</p>
@endif
@endcan
</div>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#showmap"><strong>Project Location</strong></a></li>
  <li><a data-toggle="tab" href="#details"><strong>Project Details</strong></a></li>
  <li><a data-toggle="tab" href="#contacts"><strong>Project Contacts @if(count($project->companies)>0)({{count($project->companies)}}) @endif</strong></a></li>
  <li><a data-toggle="tab" href="#branches"><strong>Nearby Branches</strong></a></li>
  <li><a data-toggle="tab" href="#notes"><strong>Project Notes @if(count($project->relatedNotes)>0) ({{count($project->relatedNotes)}}) @endif</strong></a></li>

</ul>

  <div class="tab-content">
    <div id="showmap" class="tab-pane fade in active">
      @include('projects.partials._projectmap')  
    </div>

    <div id="details" class="tab-pane fade">
      @include('projects.partials._projectdetails')   
    </div>

    <div id="contacts" class="tab-pane fade">
      @include('projects.partials._companylist')
    </div>

    <div id="branches" class="tab-pane fade">
      @include('projects.partials._branches')
    </div>

    <div id="notes" class="tab-pane fade">
      @include('projects.partials._projectnotes')
    </div>


  </div>
</div>
@include('partials._modal')
@include('partials/_scripts')
<script>
$(function(){
    $('#project_title').editable({
        url: "{{route('api.project.update',$project->id)}}",

        params: function(params) {  //params already contain `name`, `value` and `pk`
                var data = params;
                data['api_token'] = '{{auth()->check() ? auth()->user()->api_token : ''}}';
                window.console.log(data);
                return data;
              },
        
        ajaxOptions: {
            type: 'POST',
            dataType: 'JSON',
        },
        success: function( msg ) {
                $("#ajaxResponse").append("<div>"+msg+"</div>");
            }
    });
});
</script>

@stop
