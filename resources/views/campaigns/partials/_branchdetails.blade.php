@foreach ($views as $key=>$view)

  <div class="row" style="margin-top:20px;margin-bottom:20px">
        <button 
        type="button" 
        class="btn btn-campaign btn-block col-sm4" 
        data-toggle="collapse" 
        data-target="#{{$view['title']}}">
            {{$view['title']}}
        </button>
    </div>
    <div class="pl-10  @if(! $loop->first) collapse @endif " 
        id="{{$view['title']}}">
        @include('campaigns.partials._'.$key)
            {{$view['title']}}
    </div>
@endforeach