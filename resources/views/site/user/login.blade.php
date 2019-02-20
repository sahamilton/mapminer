@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
User login ::
@parent
@endsection

{{-- Content --}}
@section('content')
<div class="page-header">
	<h1>Login into your account</h1>
    

</div>
<form class="form-horizontal" method="POST" action="{{ route('login') }}" accept-charset="UTF-8">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <fieldset>
        <div class="form-group">
            <label class="col-md-2 control-label" for="email">Email</label>
            <div class="col-md-10">
                <input class="form-control" tabindex="1" placeholder="Your email" type ="email" name="email" id="email" value="{{ Input::old('email') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label" for="password">
                Password
            </label>
            <div class="col-md-10">
                <input class="form-control" tabindex="2" placeholder="password" type="password" name="password" id="password">
            </div>
        </div>

       
        @if ( Session::get('error') )
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif

        @if ( Session::get('notice') )
        <div class="alert">{{ Session::get('notice') }}</div>
        @endif

        <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
                <button tabindex="3" type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-default" href="forgot">Forgot password</a>
            </div>
        </div>
    </fieldset>
</form>

@endsection
