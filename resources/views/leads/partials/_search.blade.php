<p><form class="form-inline"  action = "{{route('leads.search')}}" method ="post" name="addressSearch">
	{{csrf_field()}}
	<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
		<label class="col-md-5 control-label">Locate by Address:</label>
           
		<input class="form-control" type="text" name="address" placeholder="address">
	<span class="help-block">
                    <strong>{{ $errors->has('address') ? $errors->first('address') : ''}}</strong>
                    </span>
                </div>
	<input type="submit" class="btn btn-success btn-sm" value="search" />
</form> </p>