@extends('site.layouts.default')
@section('content')
<div class="container" name="summaryOpportunities" >

<h4>{{$person->fullName()}}'s Summary Opportunities</h4>
<p><a href="{{route('opportunity.index')}}">Return to all Opportunities</a></p>
 @php $total = []; 
    $fields = ['open_opportunities', 'new_opportunities', 'open_value', 'won_opportunities', 'won_value']; 
@endphp

    @if($managers->count())
    @include('opportunities.partials._teamselector')
    @endif
    @include('branches.partials._periodselector')
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>    
        <thead>
            <th>Branch</th>
            <th>Open</th>
            <th>Opened</th>
            <th>Open Value</th>
            <th>Closed Won</th>
            <th>Won Value</th>
        </thead>
        <tbody>
            @foreach ($data['summary'] as $branch)
            <tr>
                <td>
                    <a href="{{route('opportunities.branch', $branch->id)}}">
                        {{$branch->branchname}}
                    </a>
                </td>
                <td align="center">
                    {{$branch->open_opportunities}}
                    
                    
                </td>
                <td align="center">{{$branch->new_opportunities}}</td>
                <td align="right">{{$branch->open_value ? "$" . number_format($branch->open_value,0) : '0'}}</td>
                <td align="center">{{$branch->won_opportunities}}</td>
               <td align="right">{{$branch->won_value ? "$" . number_format($branch->won_value,0) : '0'}}</td>
               
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th>Totals:</th>
           
                @foreach ($fields as $field)
               
                <th class="text-center">@if(strpos($field, 'value')) $ @endif{{number_format($data['summary']->sum($field),0)}}</th>
                @endforeach
   
        </tfoot>
    </table>
@include('partials._scripts')
</div>
@endsection