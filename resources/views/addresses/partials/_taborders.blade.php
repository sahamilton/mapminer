@php $total=0 @endphp
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
    <th>Period</th>
    <th>Orders</th>

    </thead>
    <tbody>
        @foreach ($location->orders as $order)
     
        <tr>
            <td>
                {{$order->branchname}}
            </td>
            <td>{{$order->pivot->period}}</td>
            <td class="text-right">${{number_format($order->pivot->orders,2)}}</td>
            @php $total = $total + $order->pivot->orders @endphp
        </tr>
        @endforeach
        <tfoot>
            <td class="text-right" colspan='3'>${{number_format($total,2)}}</td>
        </tfoot>
</tbody>