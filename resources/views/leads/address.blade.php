@extends('site/layouts/default')
@section('content')
<div class="container" style="margin-top:40px">
<form method ="post"  action ="{{route('lead.find')}}" name="leadaddress">
{{csrf_field()}}

<label>Lead Address</label>
       <input type="text" name="address" />
 
         <button type="submit"  class= "btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search!</button>

</form>
@if(count($people)>0)
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>Employee Id</th>
    <th>First Name</th>
	<th>Last Name</th>
	<th>Role</th>
	<th>Email</th>
	<th>Distance</th>
    </th>

    </thead>
    <tbody>
   @foreach($people  as $person)
   
    <tr> 
    <td>{{$person->employee_id}}</td>
			  
    <td><a href="{{route('person.show',$person->id)}}">{{$person->firstname}}</a></td>
    <td>{{$person->lastname}}</td> 
    <td>{{$person->role}}</td>
     <td>{{$person->email}}</td> 
     <td>{{number_format($person->distance_in_mi,2)}}</td> 
     </tr>
 @endforeach
</tbody>
</table>
@endif
</div>
@endsection