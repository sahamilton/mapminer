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
					{{$address->phone}}
				@elseif ($address->primaryContact->count() > 0)
					{{$address->primaryContact->first()->contactphone}}
				
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
			@php  @endphp
			<div>
			    

			    <div class="flex items-center mt-0">
			        <span class="text-sm">Your rating:</span>
			        <div class="flex items-center ml-2">
			            @for ($i = 0; $i < $ranked; $i++)
			                <i wire:click="updateRating({{$i}})" class="fa-solid fa-star text-warning"></i>
			            @endfor

			            @for ($i = $ranked; $i < 5; $i++)
			                <i wire:click="updateRating({{$i}})" class="fa-thin fa-star"></i>
			            @endfor
			        </div>
			    </div>
			</div>
			@endif
			@if(! $address->isCustomer)
				<div class="mt-0 bg-warning">
					<strong>Lead</strong>
			      @if($owned)
			      <button wire:click="changeCustomerType({{$address->id}})" title="Mark as customer" class="float-right fa-light fa-arrow-rotate-right bg-warning"></button>
			      @endif
			  </div>
			@else

				<div class="mt-0 bg-success">
					<strong>Customer </strong>
			    @if($owned)
			      <button wire:click="changeCustomerType({{$address->id}})" title="Mark as lead" class="float-right fa-light fa-arrow-rotate-left bg-success"></button>
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
				@if($address->duplicates->count() > 1 && $owned)
					<div class="alert alert-danger">
						<p><strong>Possible Duplicate(s)</strong> {{$address->duplicates->count()}}- 
							<a href="{{route('address.duplicates', $address->id)}}"><button class="btn btn-danger">Merge?</button></a></p>
					</div>
				@endif


		@if($owned)
			
			<a   wire:click="editAddress('{{$address->id}}')" 
			class="fa-solid fa-pen-to-square text-info"
			title="Edit this location"></i>
			</a>
			<a  wire:click="deleteAddress('{{$address->id}}')" 
			class="far fa-trash-alt text-danger"
			title="Remove this location from your branch"></i>
			</a>
			
	
			@include('livewire.addresses._modal')
			@include('livewire.addresses._confirmmodal')
			@include('livewire.addresses._reassignmodal')
			
		
		@else
			@include('livewire.addresses._transferrequestmodal')
		@endif
		
		</fieldset>
	</div>

		<div id = "map" style="height: 600px;width: 700px;border:solid 1px red" ></div>
	 	@include('addresses.partials.lwmap')
	
</div>