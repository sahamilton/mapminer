
<div class="col form-inline"> 

        <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
        </div>
        <select wire:model="state_code" class="form-control">
             <option value="All">All</option>
            @foreach ($states as $state)
                <option
                value="{{$state}}">{{$state}}</option>
            @endforeach
        </select>   
 
</div>