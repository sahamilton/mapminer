@auth()
    @php $newstand = new \App\Models\News;
        $news= $newstand->currentNews();
    @endphp
    @if($news->count() >0 )
        
        @include('news.newsmodal')

        @include('partials._newsscript')
    @endif
@endauth