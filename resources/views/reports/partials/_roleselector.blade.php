<div class="form-group form-group-lg">
    <label for='manager'>Role:</label>
    <select class="form-control"  multiple
        name="role[]" 
        id="role" 
        value="{{old('role')}}">
        
        @foreach ($object as $role)
        <option value="{{$role->id}}">
            {{$role->display_name}}
        </option>
        @endforeach
    </select>
    <span class="help-block">
        <strong>{{$errors->has('role') ? $errors->first('role')  : ''}}</strong>
    </span>
</div>