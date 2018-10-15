<!-- Modal -->
<div id="closelead" class="modal fade" role="dialog">
  <div class="modal-dialog">

@php 

$rank = ($lead->salesteam->first()->pivot->rating ? $lead->salesteam->first()->pivot->rating: 3);@endphp
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Close {!!$lead->companyname!!} Lead  </h4>
      </div>
      <div class="modal-body">
        <p>Please complete this form to close lead</p>
        <?php $ratings = [1,2,3,4,5];?>
        <form method="post" action="{{route('weblead.close',$lead->id)}}">
        {{csrf_field()}}
        <div class="form-group">
                    <label class="col-md-4 control-label">Lead Rating:</label>
                    <div style="font-size:150%" data-rating="{{$rank}}" id="rank" class='starrr col-md-6'></div>
                    <input type="hidden" name="ranking" id="ranking" value="{{$rank}}" />
                    
                    <select  id="ranklist">
                      @foreach ($rankingstatuses as $key=>$value)
                      <option 
                      @if($key == $rank) selected @endif value="{{$key}}">{{$value}}</option>
                      @endforeach
        </select>
        </div>

         <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Comments</label>
                <div class="col-md-6">
                    <textarea required class="form-control" name='comments' title="comments" value="{{ old('comments') }}"></textarea>
                  
                        <span class="help-block">
                        <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
                        </span>
        
                </div>
            </div>
            <div class="float-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Close Lead" class="btn btn-danger" />
            </div>
            <input type="hidden" name="lead_id" value="{{$lead->id}}" />
        </form><div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>
<script>
  $('#rank').on('starrr:change', function(e, value){
    
    $("#ranking").val(value),
    $('#ranklist').val(value);
  });
$('#ranklist').change (function(){

  $("#rank").val(this.value),
  $('#ranking').val(this.value);
});

</script>
