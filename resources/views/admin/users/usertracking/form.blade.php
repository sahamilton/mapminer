<form action="{{route('usertracking.show')}}"
    method="post" >
    @csrf

    <!-- select time frame -->
    <div class="form-group row col-sm-8 inline align-middle">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
        </div>
        <select class="" name="setPeriod" >
        @foreach (config('mapminer.timeframes') as $key=>$date)
            <option 
            @if (isset($period) && $period['period'] == $data) selected @endif
            value="{{$key}}">{{$date}}</option>
        @endforeach
        </select>
        
    </div>
    <div class="form-group{{ $errors->has('person)') ? ' has-error' : '' }}">
        <label class="col-md-3 control-label">User</label>
        <div class="col-md-6">
            <select class="form-control" name='person' required>
               
            @foreach ($persons as $person))
            <option
                value="{{$person->user_id}}">
                    {{$person->postName()}}
            </option>
                
            @endforeach


            </select>
            <span class="help-block{{ $errors->has('person)') ? ' has-error' : '' }}">
                <strong>{{ $errors->has('person') ? $errors->first('person') : ''}}</strong>
                </span>
        </div>
    </div>

    
    <!-- select model -->
    <div class="form-group{{ $errors->has('model)') ? ' has-error' : '' }}">
        <label class="col-md-3 control-label">Actions</label>
        <div class="col-md-6">
            <select class="form-control" 
            name='model[]' 
            required
            multiple>
               
            @foreach ($models as $model))
            <option
                value="{{$model}}">
                    {{$model}}
            </option>
                
            @endforeach


            </select>
            <span class="help-block{{ $errors->has('model)') ? ' has-error' : '' }}">
                <strong>{{ $errors->has('model') ? $errors->first('model') : ''}}</strong>
                </span>
        </div>
    </div>
    <input type="submit" class="btn btn-info" value = "Track Users Actions" />

</form>

