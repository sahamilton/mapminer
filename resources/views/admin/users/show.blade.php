@extends('admin.layouts.default')


{{-- Content --}}
@section('content')
	<div class="page-header">
		<h4>Users who have logged in {{$views[$view]}}</h4>
		
        @foreach ( $views as $key=>$value)
        	@if($view != $key)
        		<a href="{{route('admin.showlogins',$key)}}">{{$value}}</a> | 
       	 	@else
        		{{$value}} |
            @endif
        @endforeach
    
         <a href="{{route('users.index')}}">All Users</a>
        
	</div>

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead> 
			<tr>
           
            <th class="col-md-2">First Name</th>
            <th class="col-md-2">Last Name</th>
            <th class="col-md-2">User Name</th>
            <th class="col-md-2">EMail</th>
            <th class="col-md-2">Last Activity</th>
			</tr>
		</thead>
		<tbody>

        @foreach ($users as $user)

        <tr>
        
        <td class="col-md-2">{{ $user->firstname }}</td>
        <td class="col-md-2">{{ $user->lastname }}</td>
        <td class="col-md-2">{{ $user->username }}</td>
        <td class="col-md-2">{{ $user->email }}</td>
        <td class="col-md-2">
			@if(isset($user->lastlogin) &&  $user->lastlogin != '0000-00-00 00:00:00'  )
                <?php  $lastlogin = Carbon\Carbon::parse($user->lastlogin);?>
                {{$lastlogin->format('M j, Y h:i a')}}
			@endif
	</td>
</tr>
@endforeach
		</tbody>
	</table>
    
@include('partials/_scripts')
@stop