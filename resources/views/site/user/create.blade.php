@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Register user::
@parent
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development

{{-- Content --}}
@section('content')
<div class="page-header">
	<h1>Signup</h1>
</div>
{{ Confide::makeSignupForm()->render() }}
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
