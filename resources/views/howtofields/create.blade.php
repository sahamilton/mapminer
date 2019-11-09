@extends('admin/layouts/default')

{{-- Page content --}}
@section('content')
<div class="page-header">
    <h3> Create New How To Field </h3>
        

        <div class="float-right">
            <a href="{{ route('howtofields.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
        </div>
    
</div>

<form
name="howtofieldscreate"
method="post"
action = "{{route('howtofields.store')}}">
    @csrf
    @include('howtofields.partials._form')
    <input type= "submit"
        name="submit"
        value="Create New Field"
        class="btn btn-info" />

</form>
    


@endsection
