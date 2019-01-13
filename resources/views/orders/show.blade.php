@extends('site.layouts.default')
@section('content')
@php $total = 0; @endphp
<h2>This Period Summary Orders<span class="text text-danger">*</span></h2>
<h4>{{$branch ? $branch->branchname : ''}}</h4>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Location</th>
    <th>Orders</th>

    </thead>
    <tbody>
        @foreach ($orders as $order)
  
        <tr>
            <td>
                <a href="{{route('address.show',$order->addresses->id)}}">
                    {{$order->addresses->company->companyname}}
                </a>
            </td>
            <td>{{$order->addresses->fullAddress()}}</td>
            <td class="text-right">${{number_format($order->sum,2)}}</td>
            @php $total = $total + $order->sum @endphp
        </tr>
        @endforeach
        <tfoot>
            <td class="text-right" colspan='3'>${{number_format($total,2)}}</td>
        </tfoot>
</tbody>
</table>
<p><span class="text text-danger">*</span> From accounts providing more than $2/k month</p>
@include('partials._scripts')
@endsection
