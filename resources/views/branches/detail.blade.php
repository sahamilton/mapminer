@extends('site.layouts.default')
@section('content')
<livewire:branch-details :branch_id='$branch->id' />
    
@endsection
