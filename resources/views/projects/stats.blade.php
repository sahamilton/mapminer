@extends('admin.layouts.default')
@section('content')

<div class="container">

<h2>Construction Projects Summary</h2>

<form method="get" action ="{{route('project.stats')}}" >

<select name="id" onchange="this.form.submit()">
<option @if(! isset($source)) selected @endif value="">All Sources</option>
@foreach ($sources as $key=>$projectsource)
<option value='{{$key}}' @if(isset($source) && $projectsource==$source) selected @endif >{{$projectsource}}</option>

@endforeach
</select>
</form>


<p><a href="{{route('projects.status')}}">See all owned projects</a></p>
<p><a href="{{route('projectsource.index')}}">See all project sources</a></p>
<?php $person = null;?>
<p><strong>Total Projects:</strong>{{number_format($total[0]->total,0)}}</p>
<p><strong>Claimed / Worked Projects:</strong>{{$owned}}</p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    <th>Owner</th>
    @foreach ($statuses as $status)
    @if($status != '')
    <th>{{$status}}</th>
    @endif
    @endforeach
<th>Total</th>
<th>Rating</th>
  </thead>
  <tbody>
  <?php $grandTotal =0;?>
  @foreach ($projects as $project)
  <?php $total = 0;?>
  @if(isset($project['name']))
    <tr>
    <td><a href="{{route('project.owner',$project['id'])}}">{{$project['name']}}</a></td>
          @foreach ($statuses as $status)
            @if($status != '')
            <td style="text-align: right">
              @if(isset($project['status'][$status]))
                {{$project['status'][$status]}}
                <?php $total = $total + $project['status'][$status];?>
              @endif
            </td>
            @endif
    @endforeach
    <td  style="text-align: right">{{$total}}</td>
    <td  style="text-align: right">{{number_format($project['rating'],1)}}</td>
    <?php $grandTotal = $total + $grandTotal;?>
    </tr>
  @endif
  @endforeach
  </tbody>
  <tfoot>
  <td>Total</td>
   
    @foreach ($statuses as $status)
           
            <th style="text-align: right">
            @if(isset($projects['total']['status'][$status]))
            {{$projects['total']['status'][$status]}}
             @endif
             </th>
           

        
    @endforeach
<th style="text-align: right">{{$grandTotal}}</th>
<th></th>
  </tfoot>

</table>


</div>
@include('partials._scripts')
@stop
