<!-- Status -->
    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Status</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='status' description="status" value="{{ old('status') ? old('status') : isset($data->status) ? $data->status : "" }}" placeholder="status">
                <span class="help-block">
                    <strong>{{ $errors->has('status') ? $errors->first('status') : ''}}</strong>
                    </span>
            </div>
    </div>
 <!-- Sequence -->
     <div class="form-group{{ $errors->has('sequence') ? ' has-error' : '' }}">
         <label class="col-md-4 control-label">Sequence</label>
             <div class="col-md-6">
                 <input type="text" class="form-control" name='sequence' description="sequence" value="{{ old('sequence') ? old('sequence') : isset($data->sequence) ? $data->sequence : "" }}" placeholder="sequence">
                 <span class="help-block">
                     <strong>{{ $errors->has('sequence') ? $errors->first('sequence') : ''}}</strong>
                     </span>
             </div>
     </div>
     
    