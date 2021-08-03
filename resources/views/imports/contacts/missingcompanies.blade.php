@extends('admin.layouts.default')
@section('content')
<div class="container">
    <table >
        <thead>
            <th>Company</th>
            <th>Link To</th>
        </thead>
        <tbody>
            @foreach ($missingcompanies as $co)
                <tr>
                    <td>{{$co}}</td>
                    <td>
                        <select name="match">
                            <option value="create">Create New</option>
                            @foreach ($companies as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>

                            @endforeach
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection