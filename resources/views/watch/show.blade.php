@extends('site/layouts/default')
@section('content')

<h1>{{$user->person->firstname}} {{$user->person->lastname}}'s Watch List</h1>
<p><a href="/admin/watchlist/{{$user->id}}" 
title="Download {{$user->person->firstname}} {{$user->person->lastname}}'s Watch List as a CSV / Excel file">
<i class="glyphicon glyphicon-cloud-download"></i> Download {{$user->person->firstname}} {{$user->person->lastname}}'s Watch List</a> </p>

<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'><thead>
		@foreach($fields as $key=>$field)
			
			<th>{{$key}}</th>
			
		@endforeach
        
		</thead>
<tbody>

 @foreach($watch as $row)
<!--["Business Name"]=> string(12) "businessname" ["National Acct"]=> string(11) "companyname" ["Address"]=> string(6) "street" ["City"]=> string(4) "city" ["State"]=> string(5) "state" ["ZIP"]=> string(3) "zip" -->


			<?php reset ($fields);?>
			<tr>
			@foreach($fields as $key=>$field)
				<?php switch ($field) { 
					
					case 'businessname':
				?>
					<td>
                    <a href="/location/{{{ $row['watching'][0]->id or ''}}} ">
					{{{$row['watching'][0]->$field  or ''}}}</a>
                    </td>
				<?php		break;
					
					case 'companyname': ?>
				
					<td><a href="/company/ {{{$row['watching'][0]->company->id or ''}}}">
					{{{$row['watching'][0]->company->$field or ''}}}</a></td>
				<?php break;
						
					case 'watch_list': ?>
					<td style ="text-align: center; vertical-align: middle;">
					<input checked id="{{{$row['watching'][0]->id or ''}}}"
						type='checkbox' name='watchList'
						class='watchItem' value='{{{$row['watching'][0]->id or ''}}}' ></td>
					
				<?php break;
					
					default:?>
				
					<td>{{{$row['watching'][0]->$field or ''}}}</td>
				<?php break;
				};?>
			@endforeach
			</tr>
@endforeach
</tbody>
       </table>
@include('partials/_scripts')

@stop