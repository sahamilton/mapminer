@extends('admin.layouts.default')
@section('content')

<div class="container">

<h2>Construction Projects Summary</h2>
<p><a href="{{route('projects.status')}}">See all owned projects</a></p>
<?php $person = null;?>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    
    <th>Owner</th>
    @foreach ($statuses as $status)
    @if($status != '')
    <th>{{$status}}</th>
    @endif
    @endforeach
<th>Total</th>
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
    <?php $grandTotal = $total + $grandTotal;?>
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
<th style="text-align: right">{{$grandTotal}}</th>
  </tfoot>

</table>


</div>
@include('partials._scripts')
@stop
