@extends('site.layouts.default')
@section('content')
<div class="container" name="summaryOpportunities" >

<h4>{{$person->fullName()}}'s Summary Opportunities</h4>
<p><a href="{{route('opportunity.index')}}">Return to all Opportunities</a></p>
 @php $total = []; 
    $fields = ['open', 'created', 'openvalue', 'won', 'wonvalue']; 
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
                    {{$branch->open}}
                    
                    
                </td>
                <td align="center">{{$branch->created}}</td>
                <td align="right">{{$branch->openvalue ? "$" . number_format($branch->openvalue,0) : '0'}}</td>
                <td align="center">{{$branch->won}}</td>
               <td align="right">{{$branch->wonvalue ? "$" . number_format($branch->wonvalue,0) : '0'}}</td>
               @foreach ($fields as $field)
                    @if(isset($total[$field]))
                        @php $total[$field] = $total[$field] + $branch->$field; @endphp
                    @else
                        @php $total[$field] =  $branch->$field; @endphp
                    @endif 

               @endforeach
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th>Totals:</th>
            @foreach ($fields as $field)
            <th class="text-right">@if(strpos($field, 'value')) $ @endif{{number_format($total[$field],0)}}</th>
            @endforeach
        </tfoot>
    </table>
@include('partials._scripts')
</div>
@endsection