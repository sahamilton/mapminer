@extends('admin/layouts/default')


{{-- Page content --}}
@section('content')
<div class="container">
    <h3> Edit {{$howtofield->fieldname}}</h3>
    <p>
        <a href="{{ route('howtofields.index') }}" 
        class="btn btn-small btn-inverse">
         Back to all fields</a>
     </p>



<form method="post" 
action = "{{route('howtofields.update',$howtofield->id)}}" >
    @csrf
    @method('patch')

    @include('howtofields/partials/_form')
    <input type="submit"
        name="submit"
        class = 'btn btn-info'
        value="Update Field" />
</form>
</div>
    

@endsection
