<!-- Modal -->
<div class="modal fade" 
      id="add_contact" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Record Location Contact</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('contacts.store')}}">
        {{csrf_field()}}
        @include('contacts.partials._contactform')
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Record Contact" class="btn btn-danger" />
            </div>
        <input type="hidden" id="address_id" name="address_id" value="{{$location->id}}" />  
        </form><div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>