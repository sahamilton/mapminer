@php $total = 0; @endphp
<h2>This Period Summary Orders<span class="text text-danger">*</span></h2>
@foreach ($data['branchorders'] as $branch)
<h4>{{$branch->branchname}}</h4>
<table id ='sorttable{{$branch->id}}' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Location</th>
    <th>Orders</th>

    </thead>
    <tbody>
        
     @foreach($branch->orders as $order)
  

        <tr>
            <td>
                <a href="{{route('address.show',$order->id)}}">
                    {{$order->address->address->businessname}}
                </a>
            </td>
            <td>{{$order->address->address->fullAddress()}}</td>
            <td class="text-right">${{number_format($order->orders,2)}}</td>
            @php $total = $total + $order->orders @endphp
        </tr>
        @endforeach
        <tfoot>
            <td class="text-right" colspan='3'>${{number_format($total,2)}}</td>
        </tfoot>
</tbody>
</table>
@endforeach
<p><span class="text text-danger">*</span> From accounts providing more than $2/k month</p>