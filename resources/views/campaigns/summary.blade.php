@extends('site.layouts.default')
@section('content')
@php $totals = []; @endphp
<div class="container">
   <h2>{{$campaign->title}} Summary</h2>
    
        <p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>
        
            @include('campaigns.partials._teamselector')
        

    <table id="sorttable"
        name="branchsummary"
        class="table table-striped"
        >
        <thead>
            <th>Branch</th>
            <th>Branch Name</th>
            <th>Offered Leads</th>
            <th>Worked Leads</th>
            <th>Activities</th>
            <th>New Opportunities</th>
            <th>Opportunities Open</th>
            <th>Opportunities Won</th>
            <th>Opportunities Lost</th>
            <th>Opportunities Won Value</th>
            <th>Opportunities Open Value</th>
        </thead>
        <tbody>

            @foreach ($branches as $branch)
            @if($branch->offered_leads_count > 0)
            <tr>
                <td>
                    <a 
                    href="{{route('branchcampaign.show', [$campaign->id, $branch->id])}}">
                    {{$branch->id}}
                    </a>
                </td>
                <td>{{$branch->branchname}}</td>
                <td>
                    {{$branch->offered_leads_count}}
                    @php $totals['offered'] = isset($totals['offered']) ? $totals['offered'] + $branch->offered_leads_count : $branch->offered_leads_count  @endphp
                </td>
                <td>
                    {{$branch->leads_count}}
                    @php $totals['leads'] = isset($totals['leads']) ? $totals['leads'] + $branch->leads_count : $branch->leads_count  @endphp
                </td>
                <td>
                    {{$branch->activities_count}}
                    @php $totals['activities'] = isset($totals['activities']) ? $totals['activities'] + $branch->activities_count : $branch->activities_count  @endphp
                </td>
                <td>
                    {{$branch->opened}}
                    @php $totals['opened'] = isset($totals['opened']) ? $totals['opened'] + $branch->opened : $branch->opened  @endphp
                </td>
                <td>
                    {{$branch->open}}
                    @php $totals['open'] = isset($totals['open']) ? $totals['open'] + $branch->open : $branch->open  @endphp
                </td>
                <td>
                    {{$branch->won}}
                    @php $totals['won'] = isset($totals['won']) ? $totals['won'] + $branch->won : $branch->won  @endphp
                </td>
                <td>
                    {{$branch->lost}}
                    @php $totals['lost'] = isset($totals['lost']) ? $totals['lost'] + $branch->lost : $branch->lost  @endphp

                </td>
                <td>
                    ${{number_format($branch->wonvalue,2)}}
                    @php $totals['wonvalue'] = isset($totals['wonvalue']) ? $totals['wonvalue'] + $branch->wonvalue : $branch->wonvalue  @endphp
                </td>
                <td>
                    ${{number_format($branch->openvalue,2)}}
                    @php $totals['openvalue'] = isset($totals['openvalue']) ? $totals['openvalue'] + $branch->openvalue : $branch->openvalue  @endphp
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
        <tfoot>
            <th>Totals:</th>
            <td></td>
            <td>{{$totals['offered']}}</td>
            <td>{{$totals['leads']}}</td>
            <td>{{$totals['activities']}}</td>
            <td>{{$totals['opened']}}</td>
            <td>{{$totals['open']}}</td>
            <td>{{$totals['won']}}</td>
            <td>{{$totals['lost']}}</td>
            <td>${{number_format($totals['wonvalue'],2)}}</td>
            <td>${{number_format($totals['openvalue'],2)}}</td>
        </tfoot>
    </table>
    
</div>
@include('partials._scripts')
@endsection