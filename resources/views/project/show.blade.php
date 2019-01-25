@extends('site.layouts.default')
@section('content')

<div class="container">

<h2>Construction Project</h2>
<p><a href="{{route('projects.index')}}">Return to all projects</a> | <a href="{{route('projects.myprojects')}}">Return to my projects</a></p>
<!-- Allow owner to edit project title -->
@if($location->project->owned()  or auth()->user()->hasRole('admin'))
  <h4>
    <p><strong>Project Title:</strong>
    <a href="#" 
        id="project_title" 
        data-type="text" 
        data-pk="{{$location->project->id}}" 
        data-title="Update Project Title" 
        class="editable editable-click editable-open" 
        data-original-title="" 
        title="">
        {{$location->project->project_title}}
      </a>
    </h4>
@else
 <h4><p><strong>Project Title:</strong>{{$location->project->project_title}}</h4>
@endif

<p><strong>Address:</strong>


<blockquote>{{$location->project->street}} /{{$location->project->address2}}<br />{{$location->project->city}}, {{$location->project->state}} 
{{$location->project->zip}}

</blockquote>
<div class="row">
  <p><strong>People Ready Status:</strong>


@can('manage_projects')
  @include('project.partials._manageprojects')
@else
@if(count($location->project->owner)>0)
    {{$location->project->owner[0]->pivot->status}} by {{$location->project->owner[0]->fullName()}}</p>

  @else
    @if($location->project->owner->count()>0)
        {{$location->project->owner[0]->pivot->status}} by {{$location->project->owner[0]->postName()}}</p>
      @else
        Open</p>
    @endif
  @endif
  @endcan
</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
    <strong>Project Location</strong>
  </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
    <strong>Project Details</strong>
  </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
      <strong>Project Contacts @if($location->project->companies->count()>0)({{$location->project->companies->count()}}) @endif</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="branch-tab" data-toggle="tab" href="#branch" role="tab" aria-controls="branch" aria-selected="false">
      <strong>Nearby Branches</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="note-tab" data-toggle="tab" href="#note" role="tab" aria-controls="note" aria-selected="false">
      <strong>Project Notes</strong>
    </a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
  @include('project.partials._projectmap')  
</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
  @include('project.partials._projectdetails') 
</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
  @include('project.partials._companylist')
</div>
<div class="tab-pane fade" id="branch" role="tabpanel" aria-labelledby="branch-tab">
  @include('project.partials._branches')
</div>
<div class="tab-pane fade" id="note" role="tabpanel" aria-labelledby="note-tab">
  @include('project.partials._projectnotes')
</div>
</div>
</div>
@include('partials._modal')
@include('partials/_scripts')
<script>
$(function(){
    $('#project_title').editable({
        url: "{{route('api.project.update',$location->project->id)}}",

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

@endsection
