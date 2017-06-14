@extends('admin/layouts/default')
@section('content')
<div class="container">
<h2>Industry Vertical Analysis</h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Vertical</th>
    <th>People</th>
    <th>Current Leads</th>
    <th>Companies</th>
    <th>Locations</th>
    <th>Current Campaigns</th>
      
       
    </thead>
    <tbody>

 @foreach($verticals as $vertical)

    <tr>
    <td class="text-left">{{$vertical->filter}}</td>
    <td class="text-right">
        @if(count($vertical->people)>0)
        <a href="{{route('person.vertical',$vertical->id)}}"
        title= "See all people assigned to {{$vertical->filter}} industry">
            {{count($vertical->people)}}
            </a>
        @else
            0
        @endif
    </td>
    <td class="text-right">
        @if(count($vertical->leads)>0)
            <a href="{{route('lead.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} leads">
            {{count($vertical->leads)}}
            </a>

        @else
           0
        @endif
</td>
    <td class="text-right">
    @if(count($vertical->companies) > 0)
            <a href="{{route('company.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} companies">
            {{count($vertical->companies)}}
            </a>
    @else
        0
    @endif

   

    </td>
    <td class="text-right">{{$vertical->locations()}}</td>
    <td class="text-right">
        
        @if(count($vertical->campaigns) > 0)
            <a href="{{route('salesactivity.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} campaigns">
            {{count($vertical->campaigns)}}
            </a>
        @else
            0
        @endif
    
    </td>

    </tr>
   @endforeach
    
    </tbody>
</table>
</div>
@include('partials/_scripts')
@stop