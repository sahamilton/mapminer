@extends('site.layouts.default')
@section('content')

<livewire:mgr-summary :manager='$person->id' />
@endsection