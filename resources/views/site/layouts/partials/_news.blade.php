@php

$news = new App\Models\News;
@endphp

@if($news->currentNews()->count() >0)
    @include('news.newsmodal')
@endif
