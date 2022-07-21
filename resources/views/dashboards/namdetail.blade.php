@extends('admin.layouts.default')
@section('content')
<div class="container" name="selectManagerDashboard" >
    <livewire:namdetail :branch= '$branch_id' />
</div>
@endsection
