<div id="map-container">
	<div style="float:left;width:300px">
	
		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
			@if($address->assignedToBranch)
				@php $branch = $address->assignedToBranch->first() @endphp

			@endif
			<p>
				<i class="far fa-user" aria-hidden="true"></i>
				 <b>Primary Contact:</b> <span id="primaryContact">
				 	{{$address->primaryContact->count() ? $address->primaryContact->first()->fullname : ''}}
				 </span>
			 </p>
			 <p>
				<i class="fas fa-map-marker" aria-hidden="true"></i>
				 <b>Address:</b> <span id="primaryContact">
				 	{{$address->fullAddress()}}
				 </span>
			 </p>
			
			<p>
				<b>
					<i class="fas fa-phone" aria-hidden="true"></i> 
					Phone:
				</b>
				@if(isset($address->phone))
					<a href="tel:{{$address->phone}}">{{$address->phoneNumber}}</a>
				@elseif ($address->primaryContact->count() > 0)
					<a href="tel:{{$address->primaryContact->first()->contactphone}}">{{$address->primaryContact->first()->contactphone}}</a>
				
				@endif
				
			</p>
			
		</fieldset>
		<fieldset style="border:solid 1px grey;width:90%;padding:5px">
						<p>
			    @if($address->company)
			      <i>A location of <a href="{{ route('company.show', $address->company->id) }}">{{$address->company->companyname}}</a></a></i>
			     
			    @endif
			</p>
			@if($owned && $address->leadsource->id !=4)
				@php $ranked = $address->ranking->first()->pivot->ranking; @endphp
				<div>
				    

				    <div class="flex items-center mt-0">
				        <span class="text-sm">Your rating: {{ $ranked }}</span>
				        <div class="flex items-center ml-2">
				            @for ($i = 0; $i < $ranked; $i++)
				                <i wire:click="updateRating({{$address->id}}, '{{ $i }}')" class="fa-solid fa-star text-warning"></i>
				            @endfor

				            @for ($i = $ranked; $i < 5; $i++)
				                <i wire:click="updateRating({{$address->id}}, '{{ $i }}')" class="fa-thin fa-star"></i>
				            @endfor
				        </div>
				    </div>
				</div>
			@endif
			@if(! $address->isCustomer)
				<div class="mt-0 bg-warning">
					<strong>Lead</strong>
			      @if($owned)
			      <a wire:click="changeCustomerType({{$address->id}})" title="Mark as customer" ><i class="float-right fa-light fa-arrow-rotate-right bg-warning"></i></a>
			      @endif
			  </div>
			@else

				<div class="mt-0 bg-success">
					<strong>Customer </strong>
			    @if($owned)
			      <a wire:click="changeCustomerType({{$address->id}})" title="Mark as lead"><i class="float-right fa-light fa-arrow-rotate-left bg-success"></i></a>
			    @endif
			  </div>
			@endif 
			      

			<p><strong>Location Source:</strong> {{$address->leadsource ? $address->leadsource->source : 'unknown'}}
			@if($address->createdBy) <strong>Created by: </strong>{{$address->createdBy->person->fullname()}}@endif
			<br /><strong>Date Added:</strong> {{$address->created_at->format('Y-m-d')}}
			<br /><strong>Last Activity:</strong> {{$address->created_at->format('Y-m-d')}}</p>
			@include('addresses.partials._lwleadstatus')
			</fieldset>
			<fieldset style="border:solid 1px grey;width:90%;padding:5px">
				


		@if(count($owned) > 0)
			
			<a wire:click="editAddress('{{$address->id}}')" 
				
				title="Edit this location">
				<i class="fa-solid fa-pen-to-square text-info">
				</i>Edit
			</a>
			<a  wire:click="deleteAddress('{{$address->id}}')" 
				
				title="Remove this location from your branch"><i class="far fa-trash-alt text-danger"></i>
				Delete
			</a>
			
	
			@include('livewire.addresses._modal')
			@include('livewire.addresses._confirmmodal')
			@include('livewire.addresses._reassignmodal')
			
		
		@elseif ($address->assignedToBranch->count()>0)
			@include('livewire.addresses._transferrequestmodal')
		@endif
		
		</fieldset>
	</div>

		<div id = "map" wire:ignore style="height: 600px;width: 700px;border:solid 1px red" ></div>
	 	@include('addresses.partials.lwmap')
	
</div>