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
        value='1'
        {{(isset($howtofield) && $howtofield->required==1) ? 'checked' : ''}}
        />
    </div>
</div>

<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="active">Active:</label>
    <div class="input-group input-group-lg col-md-8">
    <input type="checkbox"
        name="active"
        {{(isset($howtofield) && $howtofield->active==0) ? '' : 'checked'}}
        
        value='1'
        />
    </div>
</div>


<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="type">Type:</label>
    <div class="input-group input-group-lg col-md-8">
    <select name="type">
        @foreach ($types as $key=>$type)
        <option value="{{$key}}"
        @if (isset($howtofield) && $howtofield->type == $type)
        selected
        @endif>{{$type}}</option>
        @endforeach
    </select>
    <label for="fieldvalues">
        Field Values:
    </label>
    
    <textarea class= "form-control" name="fieldvalues" >{{isset($howtofield) ? $howtofield->fieldvalues : ''}}</textarea>
        
    </div>
</div>

<div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="parent_id">Group:</label>
    <div class="input-group input-group-lg col-md-8">
    <select name="parent_id"  >
        <option value="{{$parents->first()->parent_id}}">Top Level</option>
        @foreach ($parents as $parent)
        <option value="{{$parent->id}}"
            @if(isset($howtofield) && $howtofield->parent_id == $parent->id)
            selected
            @endif
            >{{$parent->fieldname}}</option>
        @endforeach
    </select>
    </div>
</div>
