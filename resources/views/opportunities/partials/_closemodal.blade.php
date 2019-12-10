<div id="closeopportunity" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Close Opportunity  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Close Opportunity</strong></p>
        <form method="post" action="" name="action-form" id="action-form">
          @csrf
          <div class="form-group">
            <label class="col-md-4 control-label">Win/Loss</label>
            <select required 
            name="closed">
              <option value="2">Close - Lost</option>
              <option value="1">Close - Won</option>
            </select>
          </div>
             
          <div class="form-group">
            <label class="col-md-4 control-label">Comments</label>
            <textarea name="comments" 
            required 
            class="form-control" 
            placeholder="Explain reason for closing"></textarea>
          </div>
          <div class="form-group">
            <label class="col-md-4 control-label">Close Date</label>
           <input class="form-control actual_close" 
                    type="text" 
                    required
                    name="actual_close" 
                    autocomplete='off' 
                    id="todatepicker" 
                    value="{{  old('actual_close', isset($opportunity) && $opportunity->actual_close ? $opportunity->actual_close->format('m/d/Y') : now()->format('m/d/Y') )}}"/>
            </div>
            
            <div class="float-right">
            <input type="submit" value="Close Opportunity" class="btn btn-success" />
          </div>
        </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
