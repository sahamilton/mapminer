@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Forgot password ::
@parent
@endsection

{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>Forgot password</h1>
</div>
{{ Confide::makeForgotPasswordForm() }}
@endsection
