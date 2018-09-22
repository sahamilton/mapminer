@extends('site.layouts.default')
@section('content')
<div class= 'container'>
<h2>Document Rankings by User</h2>
<h4>{{$document->name}}</h4>
<p>{{$document->description}}</p>
<p>Posted by: {{$document->owner->username}}</p>

  <div class="row">
  <div class="col-sm-6 col-offset-2">
        <div class='table-responsive'>
<table id="sorttable" class="table table-striped table-hover table-bordered " cellspacing="0" width="100%">

        <thead>
          <th>Watched By</th>
          <th>Rank</th>

        </thead>
        <tbody> 
        @foreach ($document->rankings as $watcher)
            <tr>
                      <td>{{$watcher->person->fullName()}}</td>
                      <td>{{$watcher->pivot->rank}}</td>       
                  </tr>
        @endforeach
        </tbody>
</table>
        </div>
    </div>
    </div>
    </div>
@include('partials._scripts')
@endsection
