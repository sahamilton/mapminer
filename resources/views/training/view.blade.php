@extends('site.layouts.default')
@section('content')
<div class="container">
<h1>{{$training->title}}</h1>
<p><a href="{{route('training.index')}}">Return to all training videos</a></p>
<h4>{{$training->description}}</h4>
<iframe width="800" height="600" frameborder="0" allowfullscreen="true" style="box-sizing: border-box; margin-bottom:5px; max-width: 100%; border: 1px solid rgba(0,0,0,1); background-color: rgba(255,255,255,0); box-shadow: 0px 2px 4px rgba(0,0,0,0.1);" src="{{$training->reference}}"></iframe>
@can('manage_training')
 <div class="row">
 	<a href="{{route('training.edit',$training->id)}}">
            <i class="far fa-edit text-info"" aria-hidden="true"> </i>
            Edit Training</a>
           <a 
           data-href="{{route('training.destroy',$training->id)}}" 

                data-toggle="modal" 
                data-target="#confirm-delete" 
                data-title = "this training" 
                href="#">

                <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
                Delete Training Item
            </a>
        </div>
@endcan
</div>
<div class="row">
    <p><strong>Available for</strong>
    @foreach ($training->relatedRoles as $role)
    {{$role->display_name}}|
    @endforeach
roles</p>
</p>
</div>
<div class="row">
    <p><strong>Available for </strong>

    @foreach ($training->servicelines as $serviceline)
    {{$serviceline->ServiceLine}}|
    @endforeach
servicelines</p>
</p>
</div>
<div class="row">
    <p><strong>Available for </strong>
       
    @foreach ($training->relatedIndustries as $industry)
    {{$industry->filter}}|
    @endforeach
industries</p>
</p>
</div>
@include('partials._modal')
@include('partials._scripts')
@endsection
