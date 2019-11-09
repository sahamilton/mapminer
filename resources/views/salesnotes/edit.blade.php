@extends('admin.layouts.default')
@section('content')
<div class="container">
  <h3>Edit Sales Notes for {{$company->companyname}}</h3>


  <form name="editsalesnotes"
    method="post"
    action="{{route('salesnotes.update', $company->id)}}"
    >
    @csrf
    @method('put')
      @include('salesnotes.partials._form')
    <div style="margin-top:20px">
      <div class="controls">

        <button type="submit" class="btn btn-success">Edit Notes</button>
      </div>
    </div>
   
  </form>
 </div>
@endsection
