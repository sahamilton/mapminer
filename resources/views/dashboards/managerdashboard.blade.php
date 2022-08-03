@extends('site.layouts.default')
@section('content')

<livewire:manager-dashboard :manager='$person->id' />

@endsection
