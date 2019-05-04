<form class="form-inline" action= "{{route('lastlogged')}}" method="post">
	@csrf
	<div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
	    <label 
		    class="col-md-4 control-label" 
		    for="lastLogged">Not logged in since
		</label>
	    <div class="input-group input-group-lg">
		    <input class="form-control" 
		        type="text" 
		        name="fromdatepicker"  
		        id="fromdatepicker" 
		        value="{{  old('lastlogged', \Carbon\Carbon::now()->subMonths(1)->format('m/d/Y')) }}"/>
		    <span class="help-block">
		        <strong>{{$errors->has('lastlogged') ? $errors->first('lastlogged')  : ''}}</strong>
		    </span>
	    </div>
	    <input type="submit" name="submit" class="btn btn-success" value="Find"/>
	</div>
</form>