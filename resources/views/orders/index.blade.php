@extends('site.layouts.default')
@section('content')
@php $total = 0; @endphp
<h2>This Period Summary Orders<span class="text text-danger">*</span></h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
    <th>Manager</th>

    <th class='text-center'>Orders</th>

    </thead>
    <tbody>
        @foreach ($branchOrders as $branch)

        <tr>
            <td>
                <a href="{{route('branches.show',$branch->id)}}">
                    {{$branch->branchname}}
                </a>
            </td>
            <td>
                @if(! $branch->manager->isEmpty())
                    @foreach ($branch->manager as $manager)
                        <li> {{$manager->fullName()}}</li>
                    @endforeach
                @endif
                
            </td>


            <td class="text-right">${{number_format($orders[$loop->index],2)}}</td>
            @php $total = $total + $orders[$loop->index] @endphp
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
