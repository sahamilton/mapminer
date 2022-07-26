@extends('site.layouts.default')

{{-- Page title --}}
@section('title')
Create a New Branch
@parent
@endsection

{{-- Page content --}}
@section('content')
<div class="container">
	<div class="page-header">
		<h3>Create a New Branch</h3>
	</div>
  <form method="post" name="createbranch" action ="{{route('branches.store')}}" >
    @csrf
    @include('branches.partials._form')
      
    <input type="submit" class="btn btn-success" value="Create Branch" />
  </form>
</div>

@endsection
