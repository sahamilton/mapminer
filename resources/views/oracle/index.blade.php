@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Synchronize Mapminer with Oracle HR Data</h2>
@foreach ($actions as $step=>$act)

<div class="card mt-2">
  <div class="card-body">
    <h5 class="card-title"><span style="border-radius: 50%;
  width: 34px;
  height: 24px;
  padding: 10px;
  background: #eee;
  border: 2px solid #000;
  color: #000;
  text-align: center;
  font: 32px Arial, sans-serif;"><strong>{{$step}}</strong></span>
        <i class="{{$act['icon']}}"></i>
        {{$act['title']}}
    </h5>
    <p class="card-text">{{$act['details']}}</p>
    <a class="card-link"
        href="{{route($act['route'])}}"
            
                title = "{{$act['title']}}">
                {{$act['title']}}
            </a>
    
  </div>
</div>
    

@endforeach

@endsection
