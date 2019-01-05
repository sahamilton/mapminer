@extends('site.layouts.default')
@section('content')
@php $total = 0; @endphp
<h2>This Period Summary Orders<span class="text text-danger">*</span></h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
    <th>Manager</th>
    <th>Orders</th>

    </thead>
    <tbody>
        @foreach ($orders as $order)
     
        <tr>
            <td><a href="{{route('orders.show',$order->branches->id)}}">{{$order->branches->branchname}}
            </a></td>
            <td>
                @if(! $order->branches->manager->isEmpty())
              
                {{$order->branches->manager->first()->fullName()}}
                
               
                @endif
                
            </td>
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
