@extends('admin.layouts.default')
@section('content')
<div class="container" name="selectManagerDashboard" >
    <h2>Select Managers Dashboard</h2>
    <table id="sorttable"
        name="selectManager"
        >
        <thead>
            <th>Manager</th>
            <th>Role</th>
            <th>Reports To</th>
            <th>Branches</th>
        </thead>
        <tbody>
            @foreach ($managers as $manager)
            <tr>
                <td><a href="{{route('newdashboard.manager', $manager->id)}}">{{$manager->fullName()}}</a></td>
                <td>{{implode(",",$manager->userdetails->roles->pluck('display_name')->toArray())}}</td>
                <td>{{isset($manager->reportsTo) ? $manager->reportsTo->fullName() : ''}}</td>
                <td>@if($manager->userdetails->hasRole(['branch_manager'])) {{implode(",",$manager->branchesServiced->pluck('id')->toArray())}} @endif</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
      
@endsection
