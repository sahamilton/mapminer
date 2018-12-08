@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
 Forgot Password  ::
@parent
@endsection

{{-- Content --}}
@section('content')
<div class="page-header">
	<h1>Forgot Password</h1>
</div>
{{ Confide::makeResetPasswordForm($token)->render() }}
@endsection
