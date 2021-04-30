@php $news = new App\News;

$news = new App\News;
@endphp

@if($news->currentNews()->count() >0)
    @include('news.newsmodal')
@endif
