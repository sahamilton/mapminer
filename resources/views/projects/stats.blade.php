@extends('site.layouts.default')
@section('content')

<div class="container">

<h2>Construction Projects Summary</h2>
<?php $person = null;?>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    
    <th>Owner</th>
    @foreach ($statuses as $status)
    @if($status != '')
    <th>{{$status}}</th>
    @endif
    @endforeach

  </thead>
  <tbody>
  @foreach ($projects as $project)
  @if(isset($project['name']))
    <tr>
    <td><a href="{{route('project.owner',$project['id'])}}">{{$project['name']}}</a></td>
          @foreach ($statuses as $status)
            @if($status != '')
            <td style="text-align: right">
              @if(isset($project['status'][$status]))
                {{$project['status'][$status]}}

              @endif
            </td>
            @endif
    @endforeach
    </tr>
  @endif
  @endforeach
  </tbody>
  <tfoot>
  <td>Total</td>
   
    @foreach ($statuses as $status)
            @if($status != '')
            <th style="text-align: right">
            @if(isset($projects['total']['status'][$status]))
            {{$projects['total']['status'][$status]}}
             @endif
             </th>
           

            @endif
    @endforeach

  </tfoot>
</table>


</div>
@include('partials._scripts')
@stop
