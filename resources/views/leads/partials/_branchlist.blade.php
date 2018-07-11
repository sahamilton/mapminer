<h2>Closest Branches</h2>



<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
	    <th>Branch</th>
		<th>Manager</th>
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
				@if(count($branch->manager)>0)
					@foreach ($branch->manager as $person)
					<span id="{{$person->id}}">{{$person->postName()}}</span>
					<a class="fa fa-copy"  onclick="myFunction('#{{$person->id}}')" title="Copy {{$person->postName()}}"></a>
					@endforeach
				@endif
			</td>
			<td>{{number_format($branch->distance,0)}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
