@extends('site/layouts/default')
@section('content')
<div class="container">

<livewire:account-manager-table :manager_id='$manager_id' />



@endsection
