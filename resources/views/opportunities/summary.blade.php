@extends('site.layouts.default')
@section('content')
<div class="container" name="summaryOpportunities" >

    <h4>{{$person->fullName()}}'s Summary Opportunities</h4>
    <p><a href="{{route('opportunity.index')}}">Return to all Opportunities</a></p>
     @php $total = []; 
        $fields = ['open_opportunities', 'new_opportunities', 'open_value', 'won_opportunities', 'won_value']; 
    @endphp

    @livewire('branch-opportunity-table')
    @include('partials._scripts')
</div>
@endsection