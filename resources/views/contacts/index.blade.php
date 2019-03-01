@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
@include('maps.partials._form')
@if(isset($myBranches) && count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('contact.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{isset($data['branches']) && $data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
<h1>{{isset($title) ? $title : 'Contacts'}}</h1>  

@include('contacts.partials._table')
   
@include('partials/_scripts')

@endsection