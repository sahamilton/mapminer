<p><a href="{{route('salesnotes.company',$company->id)}}" title="Read notes on selling to {{$company->companyname}}">
<i class="fas fa-search" aria-hidden="true"></i>  
Read 'Sales Notes for {{$company->companyname}}'</a></p>
@php  @endphp
@include('salesnotes.partials._shownote')


