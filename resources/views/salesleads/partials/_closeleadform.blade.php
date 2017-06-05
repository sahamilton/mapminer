<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Close Lead</h4>
      </div>
      <div class="modal-body">
        <p>Please complete this form to close lead</p>
        <?php $ratings = [1,2,3,4,5];?>
        <form method="post" action="{{route('saleslead.close',$lead->id)}}">
        {{csrf_field()}}
            <div class="form-group{{ $errors->has('rating)') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Your Rating</label>
                <div class="col-md-6">
                    <select required class="form-control" name='rating[]'>
        
                    @foreach ($ratings as $rating))
                      <option 
                      {{null !== $rank && $rank == $rating ? 'selected' : ''}}

                      value="{{$rating}}">{{$rating}}</option>
        
                    @endforeach
        
        
                    </select>
                    <span class="help-block">
                        <strong>{{ $errors->has('rating') ? $errors->first('rating') : ''}}</strong>
                        </span>
                </div>
            </div>
            <div class="form-group{{ $errors->has('status_id)') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Closing Status</label>
                <div class="col-md-6">
                    <select class="form-control" name='status_id'>
        
                    @foreach ($sources as $key=>$value)

                      @if($key > 4)
                      <option value="{{$key}}">{{$value}}</option>
                      @endif
                    @endforeach
        
        
                    </select>
                    <span class="help-block">
                        <strong>{{ $errors->has('status_id') ? $errors->first('status_id') : ''}}</strong>
                        </span>
                </div>
            </div>
        

        
         <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">comments</label>
                <div class="col-md-6">
                    <textarea class="form-control" name='comments' title="comments" value="{{ old('comments') }}"></textarea>
                  
                        <span class="help-block">
                        <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
                        </span>
        
                </div>
            </div>
            <input type="submit" value="Close Lead" class="btn btn-danger" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>

  </div>
</div>