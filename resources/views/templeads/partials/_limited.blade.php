<div class="alert alert-danger" role="alert">
  @if(auth()->user()->hasRole('Admin'))
      {{$data['title']}} has 
  @else
  You have

  @endif

   more than 200 open leads.  Close some leads to view more.
</div>