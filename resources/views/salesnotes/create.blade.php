@extends('admin.layouts.default')
@section('content')
@php $group = NULL;@endphp
<h3>Add Sales Notes for {{$company->companyname}}</h3>
<div>
  <a href="{{route('howtofields.index')}}">Edit sales note fields</a>
</div>
@if (count($errors)>0)
  <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
          {{ $error }}<br>        
      @endforeach
  </div>
@endif

<div id='tabs'>
  <ul>

    @foreach ($groups as $tab) 
        @if (!isset($n))
            @php 
              $group = $tab;
              $n=true; 
            @endphp 
        @endif
        <li>
         <a href="#">{{$tab['group']}}</a>
       </li>
    @endforeach

  </ul>

  <div id="{{$group}}">
    <form action="{{route('salesnotes.store')" 
      method="post" 
      enctype="multipart/form-data"
      >
    @php $data = $fields;@endphp
    @include('salesnotes.partials._form')
     <input type='hidden' 
         name='companyId' 
         value="{{$company->id}}" >

    <div style="margin-top:20px">
      <div class="controls">
        <button type="submit" class="btn btn-success">Create Notes</button>
      </div>
    </div>
  </form>
  </div>
@endsection
