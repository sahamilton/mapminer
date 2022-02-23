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
      <p><a href="{{route('mark.customer', $address->id)}}" class="btn btn-success">Mark as Customer</a></p>
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

@if($address->assignedToBranch)
@php $branch = $address->assignedToBranch->first() @endphp

@endif