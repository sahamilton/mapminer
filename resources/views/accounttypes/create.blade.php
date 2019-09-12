@extends('site.layouts.default')
@section('content')
<div class="container">
    <h2>Add Account Type</h2>
    <form id="addAccountType"
        method="post"
        action="{{route('accounttype.store')}}"
        >
        @csrf
        @include('accounttypes.partials._form')
        <input type="submit" 
            name="submit" 
            class="btn btn-info"
            value= "Add Account Type" />
    </form>

</div>
@endsection