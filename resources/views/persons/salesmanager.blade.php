@extends('site/layouts/default')
@section('content')

<h2> {{$people->firstname}} {{$people->lastname}}</h2>

@if (count($people->reportsTo)==1)
<p>Reports to: {{$people->reportsTo->firstname}} {{$people->reportsTo->lastname}}
@endif
<p><a href="mailto:{{$people->email}}" title="Email {{$people->firstname}} {{$people->lastname}}">{{$people->email}}</a> </p>
<h4>{{$people->firstname}} {{$people->lastname}}'s Sales Team</h4>

  <p><a href="{{route('showmap/person',$people->id)}}"><i class="glyphicon glyphicon-flag"></i> Map View</a></p>	

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @while(list($key,$field)=each($fields))
    <th>
    {{$key}}
    </th>
    @endwhile
       
    </thead>
    <tbody>
   @foreach($directReports as $reports)
    <tr>  

	<?php reset($fields);?>
    @while(list($key,$field)=each($fields))
    <td>
    @if($field == 'name')
		<a href="{{route('person.show',$reports['id'])}}">
		{{$reports[$field]}}
		</a>

    @else
		{{$reports[$field]}}
	@endif	
    </td>
    @endwhile
    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')
@stop