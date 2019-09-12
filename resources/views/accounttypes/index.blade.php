@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Account Types</h2>
    <div class="float-right">
           <a href="{{route('accounttype.create')}}" 
               type="button" 
               class="btn btn-info" 
               >
               Add Account Type
           </a> 
           
        </div>
    <div class="col-lg-5">

        <table id="sorttable"
        class="table-sorttable table-striped table-bordered"
        >
            <thead>
                <th>Type</th>
                <th>Company Count</th>
                <th>Actions</th>

            </thead>
            <tbody>
                @foreach ($accounttypes as $type)
                <tr>
                    <td>
                        <a href="{{route('accounttype.show', $type->id)}}">{{$type->type}}</a></td>
                    <td>{{$type->companies_count}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">

                                <a class="dropdown-item"
                                     href="{{route('accounttype.edit',$type->id)}}">
                                     <i class="far fa-edit text-info"" aria-hidden="true"> </i>
                                        Edit {{$type->type}} account type
                                </a>
                                @if($type->companies_count == 0)
                                <a class="dropdown-item"
                                    data-href="{{route('accounttype.destroy',$type->id)}}" 
                                    data-toggle="modal" 
                                    data-target="#confirm-delete" 
                                    data-title = " the {{$type->type}} account type"
                                    title ="Delete {{$type->type}} account type" 
                                    href="#">
                                    <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
                                    Delete {{$type->type}} account type
                                </a>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('partials._modal')
@include('partials._scripts')
@endsection