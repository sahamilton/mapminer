@extends('admin.layouts.default')
@section('content')

<livewire:oracle-list :role='$oraclejob->job_code' />


@endsection
