

<div id="map-container">
	<div style="float:left;width:300px">

			<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			@if($address->assignedToBranch)
			@php $branch = $address->assignedToBranch->first() @endphp

			@endif
						<p>
						<i class="far fa-user" aria-hidden="true"></i>
						 <b>Primary Contact:</b> <span id="primaryContact">
						 	{{$address->primaryContact->count() ? $address->primaryContact->first()->fullName() : ''}}
						 </span>
						 </p>
						
						<p>
							<b>
								<i class="fas fa-phone" aria-hidden="true"></i> 
								Phone:
							</b>
							@if(isset($address->phone))
								{{$address->phone}}
							@elseif ($address->primaryContact->count() > 0)
								{{$address->primaryContact->first()->contactphone}}
							
							@endif
							
						</p>
						<p>
							<strong>
								<a href="" wire:click.prevent="changeview('contacts')"> Contacts</a>
							</strong>{{$address->contacts->count()}}
						</p>
			  			<p>
							<strong>
								<a href="" wire:click.prevent="changeview('activities')"> Activities</a>
							</strong>{{$address->activities_count}}
						</p>
						<p>
							<strong>
								<a href="" wire:click.prevent="changeview('opportunities')"> Opportunities</a>
							</strong>{{$address->opportunities_count}}
						</p>
						 <p>Lat: {{number_format($address->lat,4)}};<br /> Lng: {{number_format($address->lng,4)}}</p>
			</fieldset>
			<fieldset style="border:solid 1px grey;width:90%;padding:5px">
						<p>
			    @if($address->company)
			      <i>A location of <a href="{{ route('company.show', $address->company->id) }}">{{$address->company->companyname}}</a></a></i>
			     
			    @endif
			</p>
			@if($owned && $address->leadsource->id !=4)

			@include('addresses.partials._ranking')
			@endif
			<p><strong>Type:</strong>
			  @if(! $address->isCustomer)
			      Lead
			      @if($owned)
			      <p><a href="{{route('mark.customer', $address->id)}}" class="txt-success">Mark as Customer</a></p>
			      @endif
			  @else
			    Customer 
			    @if($owned)
			      <a href="{{route('mark.customer', $address->id)}}" title="Change to lead">
			        <i class="fas fa-times text-danger"></i>
			      </a>
			    @endif
			  
			  @endif
			</p>
			<p><strong>Location Source:</strong> {{$address->leadsource ? $address->leadsource->source : 'unknown'}}
			{{$address->createdBy ? "Created by " . $address->createdBy->person->fullname() : ''}}</p>


			<p><strong>Date Added:</strong> {{$address->created_at->format('Y-m-d')}}</p>
			</fieldset>
			<fieldset style="border:solid 1px grey;width:90%;padding:5px">
						 @if($address->duplicates->count() > 1 && $owned)
				<div class="alert alert-danger">
					<p><strong>Possible Duplicate(s)</strong> {{$address->duplicates->count()}}- 
						<a href="{{route('address.duplicates', $address->id)}}"><button class="btn btn-danger">Merge?</button></a></p>
				</div>
			@endif


		@if($owned)
			
			<i class="far fa-edit"
			title="Edit this location"></i>
			Edit Location</a>
			
			
				<i class="far fa-trash-alt text-danger" 
				aria-hidden="true"
				title="Delete this lead"> </i> 
			Delete Locaton
	
		
		@elseif ($address->createdBy)

			<p>Lead Created by: <a href="{{route('user.show',$address->createdBy->id)}}">{{$address->createdBy->postName()}}</a></p>

		@endif
		</fieldset>
	</div>
		<div id = "map" wire:ignore style="height: 600px;width: 700px;" ></div>
	 	@include('addresses.partials.lwmap')
	
</div>