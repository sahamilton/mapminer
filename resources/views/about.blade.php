@extends('site.layouts.default')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">About {{{env('APP_NAME')}}}</div>
               

                <div class="panel-body">
                Environment : {{{App::environment()}}} <br />
                Laravel :{{{App::version()}}}<br />
                Version : {{{env('APP_VERSION')}}} <br />
                PHP :  {{ phpversion() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
