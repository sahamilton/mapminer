<div class="form-group form-group-lg">
    <label for='manager'>Company:</label>
    <select class="form-control" 
        name="company" 
        id="company" 
        value="{{old('company')}}">
        
        @foreach ($object as $company)
        <option value="{{$company->id}}">
            {{$company->companyname}}
        </option>
        @endforeach
    </select>
    <span class="help-block">
        <strong>{{$errors->has('company') ? $errors->first('company')  : ''}}</strong>
    </span>
</div>