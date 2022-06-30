@extends('site.layouts.default')
@section('content')
<livewire:address-card :address_id='$address->id' :view='$view' />

@endsection
