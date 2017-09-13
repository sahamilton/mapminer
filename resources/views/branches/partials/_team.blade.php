<div class="container" style="margin-top:40px">

@foreach ($branchRoles as $key=>$role)
		<div class="form-group{{ $errors->has('role[$key]') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">{{$role}}:</label>
        <div class="col-md-6">
            <select multiple class="form-control" name=roles[{{$key}}][]'>
            <option @if(! isset($branchteam)) selected @endif value=''>None Assigned</option>
            	@foreach ($team as $person){
            		@if(in_array($key,$person->findRole()))
            			<option @if(isset($branchteam) && in_array($person->id,$branchteam)) selected @endif value="{{$person->id}}">{{$person->fullName()}}
            			</option>
            		@endif
            	@endforeach

            </select>
            <span class="help-block">
                <strong>{{ $errors->has('role[$key]') ? $errors->first('role[$key]') : ''}}</strong>
                </span>
        </div>
    </div>

@endforeach


</div>