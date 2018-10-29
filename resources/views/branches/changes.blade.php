@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
Review Branch Changes
@parent
@endsection

{{-- Page content --}}
@section('content')
<div class="container">
	

<ul class="nav nav-tabs">

  <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#branch"><strong>Branch Additions</strong></a></li>
  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#deletes"><strong>Branch Deletions</strong></a></li>
  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#changes"><strong>Branch Changes</strong></a></li>

</ul>

{{dd('here',$data)}}
<form method="post" name="changebranches" action ="{{route('branches.change')}}" >
@csrf
<div class="tab-content">
    <div id="branch" class="tab-pane fade in active">
      @include('branches/partials/_adds')
    </div>
	<div id="deletes" class="tab-pane fade in">
      @include('branches/partials/_deletes')
    </div>
  <div id="changes" class="tab-pane fade in">
      @include('branches/partials/_changes')
    </div>
	
</div>	
<input type="hidden" name="serviceline" value="{{$data['additionaldata]['servicelines']}}" />
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
@endsection
