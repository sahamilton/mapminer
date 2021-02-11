

        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
        </div>
        <select wire:model="state_code" class="form-control">
             <option value="All">All States</option>
            @foreach ($states as $state)
                <option
                value="{{$state}}">{{$state}}</option>
            @endforeach
        </select>   
