@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
Review Branch Changes
@parent
@endsection

{{-- Page content --}}
@section('content')
<div class="container">

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-link nav-link active" 
        id="nav-adds-tab" 
        data-toggle="tab" 
        href="#adds" 
        role="tab" 
        aria-controls="nav-adds" 
        aria-selected="true"
    >
      <strong>Branch Additions</strong>
    </a>
    <a class="nav-link" 
        id="nav-deletes-tab" 
        data-toggle="tab" 
        href="#deletes"
        role="tab" 
        aria-controls="nav-deletes" 
        aria-selected="true"
    >
      <strong>Branch Deletions</strong>
    </a>
    <a 
      class="nav-link" 
      id="nav-changes-tab" 
      data-toggle="tab" 
      href="#changes"
      role="tab" 
      aria-controls="nav-changes" 
      aria-selected="true"
    >
      <strong>Branch Changes</strong>
    </a>
  </div>
</nav>

<form method="post" name="changebranches" action ="{{route('branches.change')}}" >
@csrf

<div class="tab-content" id="nav-tabContent">
    <div id="adds" 
          class="tab-pane fade show active" 
          role="tabpanel" 
          aria-labelledby="nav-adds-tab">
      @include('imports.branches.partials._adds')
    </div>
	<div id="deletes" 
      class="tab-pane fade "
      role="tabpanel" 
      aria-labelledby="nav-deletes-tab">
      @include('imports.branches.partials._deletes')
    </div>
  <div id="changes" 
      class="tab-pane fade "
      role="tabpanel" 
      aria-labelledby="nav-adds-tab">
      @include('imports.branches.partials._changes')
    </div>
	
</div>	
<input type="hidden" name="serviceline" value="{{$data['additionaldata']['servicelines']}}" />
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
