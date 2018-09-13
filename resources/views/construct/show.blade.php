@extends('site.layouts.default')
@section('content')

<div class="container">

<h2>Construction Project</h2>
<p><a href="{{route('construction.index')}}">Return to all projects</a></p>


 <h4><p><strong>Project Title:</strong>{{$project->project_title}}</h4>


<p><strong>Address:</strong>

<blockquote>{{$project->street}} /{{$project->address2}}<br />{{$project->city}}, {{$project->state}} 
{{$project->zip}}
</blockquote>
<div class="row">
<p><strong>People Ready Status:</strong>

@can('manage_projects')
  @include('construct.partials._manageprojects')
@else
@if($project->owner)
    {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}</p>
  @else
    Open</p>
@endif
@endcan
</div>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#showmap"><strong>Project Location</strong></a></li>
  <li><a data-toggle="tab" href="#details"><strong>Project Details</strong></a></li>
  <li><a data-toggle="tab" href="#contacts"><strong>Project Contacts @if($project->companies)({{$project->companies->count()}}) @endif</strong></a></li>
  <li><a data-toggle="tab" href="#branches"><strong>Nearby Branches</strong></a></li>
  <li><a data-toggle="tab" href="#notes"><strong>Project Notes @if($project->relatedNotes) ({{$project->relatedNotes->count()}}) @endif</strong></a></li>

</ul>

  <div class="tab-content">
    <div id="showmap" class="tab-pane fade in active">
      @include('construct.partials._projectmap')  
    </div>

    <div id="details" class="tab-pane fade">
      @include('construct.partials._projectdetails')   
    </div>

    <div id="contacts" class="tab-pane fade">
      @include('construct.partials._companylist')
    </div>

    <div id="branches" class="tab-pane fade">
      @include('construct.partials._branches')
    </div>

    <div id="notes" class="tab-pane fade">
      @include('construct.partials._projectnotes')
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
