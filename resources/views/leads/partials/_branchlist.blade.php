<h2>Closest Branches</h2>



<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
	    <th>Branch</th>
		<th>Manager</th>
		<th>Sales Team</th>
		<th>Distance</th>
    </thead>
    <tbody>
	   @foreach($branches as $branch)
	    <tr>  
			<td>
				<a href="{{route('branches.show',$branch->id)}}" 
				 title="See details of branch {{$branch->branchname}}">
				{{$branch->branchname}}
				</a>
			</td>
			
			<td>
				@if($branch->manager)
					@foreach ($branch->manager as $person)
					<a href="{{route('salesorg.show',$person->id)}}"  title="See {{$person->postName()}}'s details">{{$person->postName()}}</a>

					<span type="button" class="far fa-copy btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" data-copy="{{$person->postName()}}" title="Copy to clipboard"></span>

					@endforeach
				@endif
			</td>
			<td>
				@if($branch->servicedBy)
					@foreach ($branch->servicedBy as $person)

					<a href="{{route('salesorg.show',$person->id)}}"  title="See {{$person->postName()}}'s details">{{$person->postName()}}</a>

					<span type="button" class="far fa-copy btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" data-copy="{{$person->postName()}}" title="Copy to clipboard"></span>
					@if(! $loop->last)
					<br />
					@endif
					@endforeach

				@endif
			</td>
			<td class="text text-right">{{number_format($branch->distance,1)}} miles</td>
		</tr>
		@endforeach
	</tbody>
</table>
