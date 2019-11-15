@extends('admin.layouts.default')
@section('content')
<div class="container">
<h4>Saved Reports</h4>
<p><a href="{{route('reports.index')}}">Return to Reports</a></p>
<table 
id="sorttable"
class="table table-striped"
>
    <thead>
        <th>File Name</th>
        <th>Date Created</th>
        <th>Size</th>
    </thead>
    <tbody>
        @foreach ($files as $file)
            @php
            $filename = str_replace('public/reports/', '',$file);
            $filename = str_replace('_', ' ', $filename);
            @endphp
            <tr>
                <td><a href="{{Storage::url($file)}}">{{$filename}}</a></td>
                <td>{{Carbon\Carbon::createFromTimestamp(\Storage::lastModified($file))->format('Y-m-d')}}</td>
                <td>{{\Storage::size($file)}}</td>
            </tr>

        @endforeach
    </tbody>
</table>

</div>

@endsection