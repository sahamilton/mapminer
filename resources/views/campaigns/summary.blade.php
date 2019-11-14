@extends('site.layouts.default')
@section('content')
@php $totals = []; @endphp
<div class="container">
   <h2>{{$campaign->title}} Summary</h2>
    
        <p>
            <a href="{{route('campaigns.index')}}">Return to all campaigns</a>
        </p>
        
            @include('campaigns.partials._teamselector')
        
            @include('campaigns.partials._campaignselector')
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
           
            @endforeach
        </tbody>
        <tfoot>
            
            <th>Totals:</th>
            <td></td>
            <td>{{isset($totals['offered']) ? $totals['offered'] : 0}}</td>
            <td>{{isset($totals['leads']) ? $totals['leads'] : 0}}</td>
            <td>
                {{isset($totals['activities']) ? $totals['activities'] : 0}}
            </td>
            <td>{{isset($totals['opened']) ? $totals['opened'] : 0}}</td>
            <td>{{isset($totals['open']) ? $totals['open'] : 0}}</td>
            <td>{{isset($totals['won']) ? $totals['won'] : 0}}</td>
            <td>{{isset($totals['lost']) ? $totals['lost'] : 0}}</td>
            <td>${{isset($totals['wonvalue']) ? number_format($totals['wonvalue'],2) : 0}}</td>
            <td>${{isset($totals['openvalue']) ? number_format($totals['openvalue'],2) : 0}}</td>
          
        </tfoot>
    </table>
    
</div>
@include('partials._scripts')
@endsection