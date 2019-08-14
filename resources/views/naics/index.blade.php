@extends('site.layouts.default')
@section('content')
<h2>Industry Codes</h2>

<table class="table" id = "sorttable">
    <thead>
        <th>Code</th>
        <th>Classification</th>
    </thead>
    <tbody>
        @foreach ($naics as $naic)
        <tr>
            <td>
                <a href="{{route('naic.show', $naic->id)}}">{{$naic->naics}}</a></td>
            <td>{{$naic->description}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@include('partials._scripts')
@endsection()