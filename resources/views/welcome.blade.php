@extends('site.layouts.default')
@section('content')

@if(!auth()->check())


    <div class="jumbotron" style="margin-top:30px">
        <div class="container" style="position:relative;text-align:center">
            <h4>Welcome to the PeopleReady&reg; Mapminer</h4>
            <div id="welcome">
                <div id="loginbtn" style="padding-left:0px;padding-top:200px">
                
                    <a href='login'class='btn btn-lg btn-success'>Login</a>


                </div>
            </div>
        </div>
    </div>
@else

    <div class="jumbotron" style="margin-top:30px">
        <div class="container" style="position:relative;text-align:center;min-height: 500px">
            <h4>Welcome, {{auth()->user()->person()->first()->firstname}} to the PeopleReady&reg; Mapminer</h4>
            <div id="welcome" name="welcome" class="welcome">
                
                @include('maps.partials._form')

            
            </div>
        </div>
    </div>
    <?php $newstand = new \App\News;
    $news= $newstand->currentNews();?>
    @if(! $news->isEmpty())
        @include('news.newsmodal')
    @endif

@endif

@include('partials._newsscript')
@endsection
