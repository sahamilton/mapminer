<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<?php $rank = $project->owner[0]->pivot->ranking;?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Close {!!$project->project_title!!} Lead  </h4>
      </div>
      <div class="modal-body">
        <p>Please complete this form to close project</p>
        <?php $ratings = [1,2,3,4,5];?>
        <form method="post" action="{{route('project.close',$project->id)}}">
        {{csrf_field()}}
            <div class="form-group{{ $errors->has('rating)') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Your Rating</label>
               
                <div class="col-md-6">
                    <select required class="form-control" name='rating[]'>
        
                    @foreach ($ratings as $rating))
                      <option 
                      @if($rank && $rank == $rating) selected @endif
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
        
                    @foreach ($statuses as $key=>$value)

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
                <label class="col-md-4 control-label">Comments</label>
                <div class="col-md-6">
                    <textarea class="form-control" name='comments' title="comments" value="{{ old('comments') }}"></textarea>
                  
                        <span class="help-block">
                        <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
                        </span>
        
                </div>
            </div>
            <div class="pull-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Close Project" class="btn btn-danger" />
            </div>
        </form>
      </div>
      <div class="modal-footer">
        
        
      </div>
    </div>

  </div>
</div>