@php $activities = [
  'Call',
  'Email',
  'Cold Call',
  'Sales Appointment',
  'Stop By',
  'Left material',
  'Proposal']; @endphp
  <style>
body.modal-open .activitydate, .followupdate {
    z-index: 1200 !important;
}
</style>
<!-- Modal -->
<div class="modal fade" 
      id="add_activity" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Record Lead Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('myleadsactivity.store')}}">
        {{csrf_field()}}
        @include('customer.partials._activityform')
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Record Activity" class="btn btn-danger" />
            </div>
            
        </form>

        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>