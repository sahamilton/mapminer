@extends('admin.layouts.default')
@section('content')
@php $group = null;@endphp
<h3>Edit Sales Notes for {{$company->companyname}}</h3>

<div id='tabs'>

<ul>

@foreach ($groups as $tab) 
    @if(!isset($n)) 
      @php $group = $tab;
        $n=true;
      @endphp 
    @endif
  <li>
      <a href="#{{str_replace(" ", "_", $tab['group'])}}">{{$tab['group']}}</a>
   </li>
@endforeach

</ul>

<div id="{{$group}}">
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

</div>
 <script>

$(function() {
  $("#tabs").tabs();
 
});
  </script>


@endsection
