@extends('admin.layouts.modal')

{{-- Content --}}
@section('content')

    <!-- Tabs -->
        <ul class="nav nav-tabs">

            <li class="nav-item ">
                <a class="nav-link active" 
                href="#tab-general" 
                data-toggle="tab">General</a></li>

        </ul>
    <!-- ./ tabs -->
    {{-- Delete Post Form --}}
    <form id="deleteForm" class="form-horizontal" method="post" action="@if (isset($role)){{ route(('roles.destroy', $role->id ) }}@endif" autocomplete="off">
        
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <input type="hidden" name="id" value="{{ $role->id }}" />
        <!-- <input type="hidden" name="_method" value="DELETE" /> -->
        <!-- ./ csrf token -->

        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                Delete Role
                <element class="btn-cancel close_popup">Cancel</element>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
        <!-- ./ form actions -->
    </form>
@endsection
