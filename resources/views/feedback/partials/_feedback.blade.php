
<!-- Modal -->
<div class="modal fade" 
      id="add-feedback" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Your Feedback</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('feedback.store')}}">
        {{csrf_field()}}
        @include('feedback.partials._feedbackform')
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 
           <input type="submit" value="Feedback" class="btn btn-danger" />
           {{route('feedback.store')}}
            </div>
            <input type="hidden" name="url" value="{{url()->current()}}" />
        </form>

        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>