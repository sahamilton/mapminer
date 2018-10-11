@extends('site.layouts.default')
{{-- Content --}}
@section('content')
    <!-- Tabs -->
        <h4>Are you sure you wish to delete {{$company->companyname}} and all its locations?</h4>
    <!-- ./ tabs -->

    {{-- Delete Account Form --}}
    <form id="deleteForm" class="form-horizontal" method="post" action="@if (isset($company)){{ route('company.destroy' , $company->id) }}@endif" autocomplete="off">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <input type="hidden" name="id" value="{{ $company->id }}" />
        <!-- ./ csrf token -->

        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                <element class="btn-cancel close_popup">Cancel</element>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
        <!-- ./ form actions -->
    </form>
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
