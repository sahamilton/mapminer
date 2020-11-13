@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Manage Branches</h2>

    <div class="float-right"><a href="{{route('branches.index')}}" class="btn btn-small btn-info iframe">Manage All branches</a></div>

    <ul class="nav nav-tabs">

        <li class="nav-item "><a class="nav-link active" data-toggle="tab" href="#showmap">
        <strong>Branches Without Managers<span style="color:red">*</span></strong></a>
         
        </li>
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#details">

            <strong>Managers
                <span style="color:red">*</span> Without Branches
            </strong>
        </a>
        
    </li>
    

    </ul>
    <div class="tab-content">
        <div id="showmap" class="tab-pane fade show active">
            <p>
                <a href="{{route('branches.manage.export','branches')}}">
                    <i class="far fa-file-excel"></i> 
                Export Branches without Managers to Excel
                </a> 
            </p>
            @include('admin.branches.partials._branches')
        </div>
        
        <div id="details" class="tab-pane fade ">
            <p>
                
                <a href="{{route('branches.manage.export','managers')}}">
                    <i class="far fa-file-excel"></i> 
                    Export Managers Without Branches to Excel
                </a>
            </p>
             @include('admin.branches.partials._managers')
        </div>


    </div>
</div>
@include('partials/_scripts')
@endsection