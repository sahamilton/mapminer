<table class="table" >
	<thead>
		<th>
			 <a wire:click.prevent="sortBy('title')" role="button" href="#">
                Campaign
                @include('includes._sort-icon', ['field' => 'title'])
            </a>
			
		</th>
		<th>
			<a wire:click.prevent="sortBy('created_at')" role="button" href="#">
				Created
				@include('includes._sort-icon', ['field' => 'created_at'])
            </a>
		</th>
		<th>
			<a wire:click.prevent="sortBy('datefrom')" role="button" href="#">
				Date From
				@include('includes._sort-icon', ['field' => 'datefrom'])
            </a>
		</th>
		<th>
			<a wire:click.prevent="sortBy('dateto')" role="button" href="#">
				Date To
				@include('includes._sort-icon', ['field' => 'dateto'])
            </a>
		</th>
		<th>Author</th>
		<th>Organization</th>
		<th>Status</th>
		<th>Branches</th>
		<th>Actions</th>
	</thead>
	<tbody>
		@foreach ($campaigns as $campaign)
		
		<tr>
			<td>
				<a href="{{route('campaigns.show',$campaign->id)}}"
					title="See details of this campaign">
					{{$campaign->title}}
				</a>
			</td>
			<td>{{$campaign->created_at->format('Y-m-d')}}</td>
			<td>{{$campaign->datefrom->format('Y-m-d')}}</td>
			<td>{{$campaign->dateto->format('Y-m-d')}}</td>
			<td>{{$campaign->author ? $campaign->author->fullName() : ''}}</td>
			<td>{{$campaign->manager ? $campaign->manager->fullName() : ''}}</td>
			<td>{{$campaign->status}}</td>
			<td>{{$campaign->branches_count}}</td>
			
			<td>
				@if($campaign->status == 'planned')
				<a 
				 	data-href="{{route('campaigns.destroy',$campaign->id)}}" 
					data-toggle="modal" 
					data-target="#confirm-delete" 
					data-title = "campaign"
					title ="Delete this campaign" 
					href="#">

					<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> </a>
				@endif
		</tr>
		@endforeach
	</tbody>
</table>