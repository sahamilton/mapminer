<!-- Modal -->
@php
$rank =  3 ;@endphp

<div id="closeopportunity" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Close {!!$location->businessname!!} Opportunity  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Close Opportunity</strong></p>
        <form method="post" action="{{route('opportunity.close',$location->id)}}">
          @csrf
          <div class="form-group">
            <label class="col-md-4 control-label">Win/Loss</label>
            <select required name="close">
              <option value="close">Close - Lost</option>
              <option value="converted">Close - Won</option>
            </select>
          </div>
          <div class="form-group">
            <label class="col-md-4 control-label">Estimated Headcount:</label>
           
              <input class="form-control" type="text" name="requirements" />
       
          </div>
          <div class="form-group">
            <label class="col-md-4 control-label">Estimated Duration: (months):</label>
           
              <input class="form-control" type="text" name="durations" />
       
          </div>
          <div class="form-group">
            <label class="col-md-4 control-label">Estimated Revenue:</label>
            
              <input class="form-control" type="text" name="periodbusiness" />
           
          </div>
          <div class="form-group">
            <label class="col-md-4 control-label">Client Id:</label>
           
              <input class="form-control" type="text" name="client_id"
              @if($location->company)
              value="{{$location->company->customer_id }}" 
              readonly
              @endif
              />
          
          </div>

          <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Comments</label>
            
            <textarea required 
            class="form-control" name='comments' 
            title="comments" 
            value="{{ old('comments') }}"></textarea>
       
            <span class="help-block">
            <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
            </span>
          </div>
        
          <div class="float-right">
            <input type="submit" value="Close Opportunity" class="btn btn-success" />
          </div>
          <input type="hidden" name="address_id" value="{{$location->id}}" />
          <input type="hidden" name="opportunity_id" value="{{$location->opportunities->id}}" />
        </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
