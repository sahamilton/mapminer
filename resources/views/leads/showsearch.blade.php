@extends ('admin.layouts.default')
@section('content')
<h2>Assign Leads</h2>
<p><a href="{{route('leadsource.show',$lead->lead_source_id)}}">Show All WebLeads</a></p>
<div class="container">
  <div class="row">
    <div class="col-sm-6">
    	@include('leads.partials._search')
      <div class="panel panel-default">
	
			<form action="{{route('leads.store')}}" method="post">
				{{csrf_field()}}
			@include('leads.partials._form')
			<input class="btn btn-info pull-right" type="submit" name = "submit" value="Save New Lead" />
		</form>

		
	</div>
    </div>
    <div class="col-sm-6">
      
      
<div id="map"  style="width:100%;border:solid 1px red"></div>
		@include('leads.partials._branchlist')	
		@include('leads.partials._repslist')


</div>
    </div>
  </div>
</div>


<!-- <script>
function myFunction(element) {

  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();

  document.execCommand("copy");
  $temp.remove();
  
}

</script>-->	
@include('leads.partials.map')

@include('partials/_scripts')
@endsection

