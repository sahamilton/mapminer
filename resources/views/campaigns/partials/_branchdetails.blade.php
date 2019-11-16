@foreach ($views as $key=>$view)
  <div class="row" style="margin-top:20px;margin-bottom:20px">
        <button 
        type="button" 
        class="btn btn-info btn-block col-sm4" 
        data-toggle="collapse" 
        data-target="#{{$view}}">
            {{$view}}
        </button>
    </div>
    <div class="pl-10  @if(! $loop->first) collapse @endif " 
        id="{{$view}}">
        @include('campaigns.partials._'.$key)
            {{$view}}
    </div>
@endforeach