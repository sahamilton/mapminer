@extends('site.layouts.default')
@section('content')

<livewire:activities-table :branch='$branch->id' :status='0' />
@endsection