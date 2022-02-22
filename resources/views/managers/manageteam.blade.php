@extends('site.layouts.default')
@section('content')

@livewire('manage-team', ['user'=>$user])
@endsection