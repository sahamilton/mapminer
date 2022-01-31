@extends('admin.layouts.default')

{{-- Content --}}
@section('content')
    <h2>Paste in Employee IDs to be deactivated</h2>
    {{-- Delete Sync User Form --}}
    <form id="deleteSyncForm" 
    class="form-horizontal" 
    method="post" 
    action="{{ route('users.sync.confirm') }}" 
    autocomplete="off">
        <!-- CSRF Token -->
       @csrf()
        
        <!-- ./ csrf token -->
        <div class="form-group">
            
                <textarea
                class="form-control"
                name="employee_ids"></textarea>
            
        </div>
        <!-- Form Actions -->
        <div class="form-group">
            <div class="controls">
                
                <button type="submit" class="btn btn-danger">Deactivate</button>
            </div>
        </div>
        <!-- ./ form actions -->
    </form>
@endsection
