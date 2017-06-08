@extends('site.layouts.default')
@section('content')

<h2> {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}'s Sales Team</h2>

@if(isset ($salesteam[0]->userdetails) && $salesteam[0]->userdetails->email !='' )

    @if (count($salesteam[0]->userdetails->roles)==1)
    <h4> {{$salesteam[0]->userdetails->roles[0]->name}}</h4>
    @endif
    <p><span class="glyphicon glyphicon-envelope"></span> 
    <a href="mailto:{{$salesteam[0]->userdetails->email}}" title="Email {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}">{{$salesteam[0]->userdetails->email}}</a> </p>
@endif

@if (isset($salesteam[0]->reportsTo) && count($salesteam[0]->reportsTo->userdetails) == 1)
<p>Reports to: <a href = "{{route('salesorg.list',$salesteam[0]->reportsTo->id)}}" 
title= "See {{$salesteam[0]->reportsTo->firstname}} {{$salesteam[0]->reportsTo->lastname}}'s sales team"> {{$salesteam[0]->reportsTo->firstname}} {{$salesteam[0]->reportsTo->lastname}}  {{count($salesteam[0]->reportsTo->userdetails->roles) !=0 ? ' - ' . $salesteam[0]->reportsTo->userdetails->roles[0]->name : ''}}</a>
@endif




  <p><a href="{{route('salesorg',$salesteam[0]->id)}}"
  title="See map view of {{$salesteam[0]->firstname}} {{$salesteam[0]->lastname}}'s sales team"><i class="glyphicon glyphicon-flag"></i> Map View</a></p>    

@include('leads.partials.search')
<table id ='sorttablenosort' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th> Name </th> 
    <th> Role </th>
    <th> Reports to </th>
    <th>City</th>
    <th>State</th>
    <th>Verticals</th>
   
   
    </thead>
    <tbody>
  
    <?php

    ?>
   @foreach($salesteam as $reports)

   @if($reports->id != $salesteam[0]->id)

    <tr>  

    <td>
    {{str_repeat ( '&nbsp;' , ($reports->depth - $salesteam[0]->depth) * 3 )}} 
        @if($reports->isLeaf())
        <a href="{{route('salesorg',$reports->id)}}"
        title="See {{$reports->firstname . " " . $reports->lastname}}'s sales area">
        {{$reports->firstname . " " . $reports->lastname}}
        </a>
        @else
        <a href="{{route('salesorg.list',$reports->id)}}"
        title="See {{$reports->firstname . " " . $reports->lastname}}'s sales team">
        {{$reports->firstname . " " . $reports->lastname}}
        </a>
        @endif

   </td>

   <td>
       @if(isset($reports->userdetails->roles)) 
         @foreach($reports->userdetails->roles as $role)
            {{$role->name}}<br />
        @endforeach
        @endif
        
    </td>

   <td>
    @if(isset($reports->reportsTo))

      {{$reports->reportsTo->firstname}} {{$reports->reportsTo->lastname}}
    @endif
   
   @if($reports->isLeaf())
     <td> {{$reports->city}}</td><td>{{$reports->state}}</td>
    @else
    <td></td><td></td>  
   @endif
  
   <td>
   <ul>
     @foreach ($reports->industryfocus as $vertical)
     <li>{{$vertical->filter}}</li>
     @endforeach
     </ul>
   </td>
    
   
    </tr>
    @endif
   @endforeach
    
    </tbody>
    </table>





@include('partials._scripts')
@stop