@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>Edit {{$accounttype->type}} Account Type</h2>
    <form id="editdAccountType"
        method="post"
        action="{{route('accounttype.update', $accounttype->id)}}"
        >
        @csrf
        @method('put')
        @include('accounttypes.partials._form')
        <input type="submit" 
            name="submit" 
            class="btn btn-info"
            value= "Edit Account Type" />
    </form>

</div>
@endsection