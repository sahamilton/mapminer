@extends('admin/layouts/default')

{{-- Page content --}}
@section('content')
<h2>Manage Sales Notes</h2>
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    <th>Company</th>
    <th>Sales Notes</th>
    <th>Action</th>
  </thead>
  <tbody>

@foreach ($salesnotes as $salesnote)

 @if($salesnote['salesnotes']=='Yes')
                <tr class='success'>
                 @else
               <tr class='danger'>
                @endif

<td> {{$salesnote['name']}} </td>

<td> {{$salesnote['salesnotes']}}</td>
<td>
  <div class="btn-group">
      @if($salesnote['salesnotes']=='Yes')
        <a href="{{route('salesnotes.edit',$salesnote['id'])}}"
        title=" Edit {{trim($salesnote['name'])}}'s Sales Notes">
      @else
        <a href="{{route('salesnotes.create','company='.$salesnote['id'])}}"
        title=" Create {{trim($salesnote['name'])}}'s Sales Notes">
      @endif
      <button type="button" class="btn btn-success" >
      <i class="glyphicon glyphicon-pencil" ></i></button>   
      </a>
  	          
  </div>
</td>
@endforeach
</tbody>
</table>

@include('partials/_scripts')
@stop
        