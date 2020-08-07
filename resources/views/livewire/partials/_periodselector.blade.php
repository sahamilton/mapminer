<div class="col form-inline"> 

        <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
        </div>
        <select wire:model="setPeriod" class="form-control">
           
            @foreach (config('mapminer.timeframes') as $key=>$period)
                <option
                value="{{$key}}">{{$period}}</option>
            @endforeach
        </select>   
 
</div>