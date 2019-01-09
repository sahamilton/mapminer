<!-- Modal -->
<div class="modal fade" 
      id="add_lead" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Add Lead</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form name="createlead" action="{{route('myleads.store')}}" method="post">
        {{csrf_field()}}
        @include('customer.partials._form')
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Add Lead" class="btn btn-danger" />
            </div>
            
        </form><div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>