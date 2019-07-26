<style>
    .ui-datepicker { width: 17em; padding: .2em .2em 0; display: none; z-index: 2000 !important;}

</style>
<!-- Modal -->
<div class="modal fade" 
      id="run-report"
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myModalLabel" 
      aria-hidden="true">

  <div class="modal-dialog">


    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Set Report Parameters</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <div class="modal-body">
                
        <form id="period-form" 
            action="" 
            method="post" 
            >
           @csrf
        @include('reports.partials._parameterform')
          <div class="float-right">
           <button 
           type="button" 
           class="btn btn-default" 
           data-dismiss="modal">Cancel</button> 
           <input type="submit" 
           value="Run Report" 
           class="btn btn-danger" />
            </div>
            
        </form>

        <div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>

