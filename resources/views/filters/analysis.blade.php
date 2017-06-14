@extends('admin/layouts/default')
@section('content')
<div class="container">
<h2>Industry Vertical Analysis</h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Vertical</th>
    <th>People</th>
    <th>Leads</th>
    <th>Companies</th>
    <th>Locations</th>
    <th>Campaigns</th>
      
       
    </thead>
    <tbody>

 @foreach($verticals as $vertical)

    <tr>  
    <td>{{$vertical->filter}}</td>
    <td>{{count($vertical->people)}}</td>
    <td>
        @if(count($vertical->leads)>0)
            <a href="{{route('lead.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} leads">
            {{count($vertical->leads)}}
            </a>

        @else
           0
        @endif
</td>
    <td>
    @if(count($vertical->companies) > 0)
            <a href="{{route('company.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} companies">
            {{count($vertical->companies)}}
            </a>
    @else
        0
    @endif

   

    </td>
    <td>{{$vertical->locations()}}</td>
    <td>
        
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