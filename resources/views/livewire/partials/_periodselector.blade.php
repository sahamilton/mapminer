<div class="col form-inline"> 

        <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
        </div>
        <select wire:model="setPeriod" class="form-control">
           <option value="All">All</option>
            @foreach (config('mapminer.timeframes') as $key=>$per)
                <option
                value="{{$key}}">{{$per}}</option>
            @endforeach
        </select>   
 
</div>