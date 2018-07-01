<p><form action = "{{route('leads.search')}}" method ="post" name="addressSearch">
	{{csrf_field()}}<label>Search Address:</label>
	<input type="text" name="address" placeholder="address">
	<input type="submit" class="btn btn-success btn-xs" value="search" />
</form> </p>