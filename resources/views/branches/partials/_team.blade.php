<div class="container">

@foreach ($branchRoles as $key=>$role)
		<div class="form-group{{ $errors->has('role[$key]') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">{{$role}}:</label>
        <div class="col-md-6">
            <select multiple class="form-control" name=role[{{$key}}][]'>
            <option "value"=''>None Assigned</option>


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('role[$key]') ? $errors->first('role[$key]') : ''}}</strong>
                </span>
        </div>
    </div>

@endforeach


</div>