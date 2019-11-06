<div class="form-group{{ $errors->has('fieldname') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="fieldname">FieldName</label>
    <div class="input-group input-group-lg col-md-8">
    
    <input type="text"
        name="fieldname"

        value="{{old('fieldname', isset($howtofield) ? $howtofield->fieldname :'' )}}" />
        {!! $errors->first('fieldname', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('required') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="required">Required:</label>
    <div class="input-group input-group-lg col-md-8">
    <input type="checkbox"
        name="required"
        />
    </div>
</div>


<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="type">Type:</label>
    <div class="input-group input-group-lg col-md-8">
    <select name="type">
        @foreach ($types as $key=>$type)
        <option value="{{$key}}">{{$type}}</option>
        @endforeach
    
    <label for="values">
        Values:
    </label>
    <div class= "form-control" >
    <textarea name="values" ></textarea>
        
    </div>
</div>

<div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="group">Group:</label>
    <div class="input-group input-group-lg col-md-8">
    <select name="group"  >
        @foreach ($groups as $group)
        <option value="{{$group->group}}">{{$group->group}}</option>
        @endforeach
    </select>
    </div>
</div>
<p style ="margin-top: 10px">
    <input type='text' id="addGroup" name='addGroup' /><button type="button"  id="add" >  <i class="fas fa-plus text-success" aria-hidden="true"></i> Add Group</button></p>
