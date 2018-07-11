<p><form class="form-inline"  action = "{{route('leads.search')}}" method ="post" name="addressSearch">
	{{csrf_field()}}
	<label class="col-md-2 control-label">Locate by Address:</label>
           
	<input class="form-control" type="text" name="address" placeholder="address">
	<input type="submit" class="btn btn-success btn-sm" value="search" />
</form> </p>