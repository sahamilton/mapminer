@extends('site.layouts.default')

{{-- Page title --}}
@section('title')
Review Branch Changes
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="container">
	

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#branch"><strong>Branch Additions</strong></a></li>
  <li><a data-toggle="tab" href="#team"><strong>Branch Deletions</strong></a></li>


</ul>


<form method="post" name="changebranches" action ="{{route('branches.change')}}" >
{{csrf_field()}}
<div class="tab-content">
    <div id="branch" class="tab-pane fade in active">
      @include('branches/partials/_adds')
    </div>
	<div id="team" class="tab-pane fade in">
      @include('branches/partials/_deletes')
    </div>
	
</div>	
<input type="hidden" name="serviceline" value="{{$data['serviceline']}}" />
<input type="submit" class="btn btn-success" value="Update Branches" />
</form>
</div>
@include('partials/_scripts')

<script>
  var table = $('#sorttable').DataTable({
   
});
  $('changebranches').on('submit', function(e){
   var $form = $(this);

   // Iterate over all checkboxes in the table
   table.$('input[type="checkbox"]').each(function(){
      // If checkbox doesn't exist in DOM
      if(!$.contains(document, this)){
         // If checkbox is checked
         if(this.checked){
            // Create a hidden element 
            $form.append(
               $('<input>')
                  .attr('type', 'hidden')
                  .attr('name', this.name)
                  .val(this.value)
            );
         }
      } 
   });          
});
</script>
@stop