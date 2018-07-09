<p><form action = "{{route('leads.search')}}" method ="post" name="addressSearch">
	{{csrf_field()}}
	<label class="col-md-2 control-label">Locate by Address:</label>
           
	<input type="text" name="address" placeholder="address">
	<input type="submit" class="btn btn-success btn-xs" value="search" />
</form> </p>