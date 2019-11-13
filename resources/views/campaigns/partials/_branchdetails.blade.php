@foreach ($views as $view)
  <div class="row" style="margin-top:20px;margin-bottom:20px">
        <button 
        type="button" 
        class="btn btn-info btn-block col-sm4" 
        data-toggle="collapse" 
        data-target="#{{$view}}">
            {{ucwords(parseCamelCase($view))}}
        </button>
    </div>
    <div class="pl-10  @if(! $loop->first) collapse @endif " 
        id="{{$view}}">
        @include('campaigns.partials._'.$view)

        {{$view}}
    </div>
@endforeach