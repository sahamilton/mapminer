@extends('site.layouts.default')
@section('content')
@php $totals = []; @endphp
<div class="container">
   <h2>{{$manager->fullName()}} {{$campaign->title}} Summary</h2>
    @php $route = 'campaigns.report'; @endphp
    <p><a href="{{route('campaigns.manager', [$campaign->id, $manager->reportsTo->id])}}">View {{$manager->reportsTo->fullName()}} Campaign Summary</a></p>
    @include('campaigns.partials._teamselector')
    <p><a href="{{route('campaigns.show', $campaign->id)}}">Return campaign</a></p>
    <p><a href="{{route('campaigns.company', $campaign->id)}}">Show Company Stats</a></p>
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
            <th># New</th>
            <th># Open</th>
            <th># Won</th>
            <th># Lost</th>
            <th>$ Won</th>
            <th>$ Open</th>
        </thead>
        <tbody>

            @foreach ($branches as $branch)

            <tr> 
                <td>
                    <a 
                    href="{{route('branchcampaign.show', [$campaign->id, $branch->id])}}">
                    {{$branch->id}}
                    </a>
                </td>
                <td>{{$branch->branchname}}</td>
                <td>
                    {{$branch->offered_leads}}
                    @php $totals['offered'] = isset($totals['offered']) ? $totals['offered'] + $branch->offered_leads : $branch->offered_leads  @endphp
                </td>

                <td>
                    {{$branch->worked_leads}}
                 
                    @php $totals['worked'] = isset($totals['worked']) ? $totals['worked'] + $branch->worked_leads : $branch->worked_leads  @endphp
                </td>
                <td>
                    {{$branch->activities_count}}
                    @php $totals['activities'] = isset($totals['activities']) ? $totals['activities'] + $branch->activities_count : $branch->activities_count  @endphp
                </td>
                <td>
                    
                    {{$branch->new_opportunities}}
                    @php $totals['opened'] = isset($totals['opened']) ? $totals['opened'] + $branch->new_opportunities : $branch->new_opportunities  @endphp
                </td>
                <td>
                    {{$branch->opportunities_open}}
                    @php $totals['open'] = isset($totals['open']) ? $totals['open'] + $branch->opportunities_open : $branch->opportunities_open  @endphp
                </td>
                <td>
                    {{$branch->won_opportunities}}
                    @php $totals['won'] = isset($totals['won']) ? $totals['won'] + $branch->won_opportunities : $branch->won_opportunities  @endphp
                </td>
                <td>
                    {{$branch->lost_opportunities}}
                    @php $totals['lost'] = isset($totals['lost']) ? $totals['lost'] + $branch->lost_opportunities : $branch->lost_opportunities  @endphp

                </td>
                <td class="text-right">
                    ${{number_format($branch->won_value,0)}}
                    @php $totals['wonvalue'] = isset($totals['wonvalue']) ? $totals['wonvalue'] + $branch->won_value : $branch->won_value  @endphp
                </td>
                <td class="text-right">
                    ${{number_format($branch->open_value, 0)}}
                    @php $totals['openvalue'] = isset($totals['openvalue']) ? $totals['openvalue'] + $branch->open_value : $branch->open_value  @endphp
                </td>
            </tr>
            
            @endforeach
        </tbody>
        <tfoot>
            <th>Totals:</th>
            <td></td>
            <td>{{isset($totals['offered']) ? $totals['offered'] :0}}</td>
            <td>{{isset($totals['worked']) ? $totals['worked'] : 0}}</td>
            <td>{{isset($totals['activities']) ? $totals['activities'] : 0}}</td>
            <td>{{isset($totals['opened']) ? $totals['opened'] : 0}}</td>
            <td>{{isset($totals['open']) ? $totals['open'] : 0}}</td>
            <td>{{isset($totals['won']) ? $totals['won'] : 0}}</td>
            <td>{{isset($totals['lost']) ? $totals['lost'] : 0}}</td>
            <td class="text-right">${{isset($totals['wonvalue']) ? number_format($totals['wonvalue'],0) : 0}}</td>
            <td class="text-right">${{isset($totals['openvalue']) ? number_format($totals['openvalue'],0) :0 }}</td>
        </tfoot>
    </table>
    
</div>
@include('partials._scripts')
@endsection