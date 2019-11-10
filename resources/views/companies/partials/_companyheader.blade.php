<p><a href="{{route('salesnotes.company',$company->id)}}" title="Read notes on selling to {{$company->companyname}}">
<i class="fas fa-search" aria-hidden="true"></i>  
Read 'How to Sell to {{$company->companyname}}'</a></p>
@php ($data = $salesnote); @endphp
@include('salesnotes.partials._shownote')


