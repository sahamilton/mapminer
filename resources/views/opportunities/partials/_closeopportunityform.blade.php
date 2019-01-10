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
        <p><strong>Close or Convert Opportunity</strong></p>
        <form method="post" action="{{route('opportunity.close',$location->id)}}">
          @csrf
          <div class="form-group">
            <label class="col-md-2 form-control control-label">Close Opportunity:</label>
            <div class="form-control">
              <input type="radio" name="close" value="close" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label">Convert Opportunity:</label>
            <div class="form-control">
              <input type="radio" name="close" selected value="convert" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label">Estimated Requirements:</label>
            <div class="form-control">
              <input type="text" name="requirements" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label">Estimated Business:</label>
            <div class="form-control">
              <input type="text" name="periodbusiness" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label">Client Id:</label>
            <div class="form-control">
              <input type="text" name="client_id" />
            </div>
          </div>

          <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Comments</label>
            <div class="col-md-6">
            <textarea required class="form-control" name='comments' title="comments" value="{{ old('comments') }}"></textarea>
          </div>
            <span class="help-block">
            <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
            </span>
          </div>
        
          <div class="float-right">
            <input type="submit" value="Close Opportunity" class="btn btn-success" />
          </div>
          <input type="hidden" name="address_id" value="{{$location->id}}" />
        </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
