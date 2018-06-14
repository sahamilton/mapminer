@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Manage Branches</h2>

    <div class="pull-right"><a href="{{route('branches.index')}}" class="btn btn-small btn-info iframe">Manage All branches</a></div>

    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#showmap">
        <strong>Branches Without Managers<span style="color:red">*</span></strong></a></li>
    <li>
        <a data-toggle="tab" href="#details">
            <strong>Managers
                <span style="color:red">*</span> Without Branches
            </strong>
        </a>
    </li>
    

    </ul>
    <div class="tab-content">
        <div id="showmap" class="tab-pane fade in active">
            @include('admin.branches.partials._branches')
        </div>
        
        <div id="details" class="tab-pane fade in">
             @include('admin.branches.partials._managers')
        </div>


    </div>
</div>
@include('partials/_scripts')
@endsection