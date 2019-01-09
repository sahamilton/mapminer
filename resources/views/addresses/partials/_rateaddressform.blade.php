<!-- Modal -->
@php
$rank =  3 ;@endphp
<div id="rateaddress" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Rate {!!$location->businessname!!} data  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Please complete this form to rate this locations data</p>
        <?php $ratings = [1,2,3,4,5];?>
        <form method="post" action="{{route('address.rating',$location->id)}}">
          @csrf
          <div class="form-group">
            <label class="col-md-2 control-label">Address Rating:</label>
            <div style="font-size:150%" 
              data-rating="{{$rank}}" 
              id="rank" 
              class='starrr col-md-4'>
            </div>
              <input type="hidden" name="ranking" id="ranking" value="{{$rank}}" />

              <select class="form-control input-sm" id="ranklist">
                @foreach ($location->addressStatusOptions as $key=>$value)
                  <option 
                  @if($key == $rank) selected @endif value="{{$key}}">
                  {{$value}}
                </option>
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
        
          <div class="float-right">
            <input type="submit" value="Rate Location" class="btn btn-success" />
          </div>
          <input type="hidden" name="address_id" value="{{$location->id}}" />
        </form>
      </div>
      <div class="modal-footer">

        Footer
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
