<style>

.activity_date, .followup_date{z-index:1151 !important;}
</style>
@php $activityTypes = \App\Models\ActivityType::all(); @endphp
<!-- Modal -->
<div class="modal fade" 
      id="add-activity" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Record Activity</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
                
        <form method="post" action="{{route('activity.store')}}">
        {{csrf_field()}}
        @include('opportunities.partials._activityform')
          <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 
           <input type="submit" value="Record Activity" class="btn btn-danger" />
            </div>
            <input type="hidden" name= "address_id" id = "address_id" value="" />
            <input type="hidden" name= "branch_id" id = "branch_id" value="" />
        </form>

        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>