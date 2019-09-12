@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>Account Types</h2>
    <div class="col-lg-5">
        <table id="sorttable"
        class="table-sorttable table-striped table-bordered"
        >
            <thead>
                <th>Type</th>
                <th>Company Count</th>

            </thead>
            <tbody>
                @foreach ($accounttypes as $type)
                <tr>
                    <td>
                        <a href="{{route('accounttype.show', $type->id)}}">{{$type->type}}</a></td>
                    <td>{{$type->companies_count}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('partials._scripts')
@endsection