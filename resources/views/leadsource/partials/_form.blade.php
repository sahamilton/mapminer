<!-- source -->
    <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Source Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='source' description="source" value="{{ old('source') ? old('source') : isset($leadsource->source) ? $leadsource->source : '' }}" placeholder="source">
                <span class="help-block">
                    <strong>{{ $errors->has('source') ? $errors->first('source') : ''}}</strong>
                    </span>
            </div>
    </div>

   <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Description</label>
            <div class="col-md-6">
                <textarea class="form-control" name='description' title="description">
                {{ old('description') ? old('description') : isset($leadsource->description) ? $leadsource->description : ''}}
                </textarea>
              
                    <span class="help-block">
                    <strong>{{$errors->has('description' ? $errors->first('description')  : ''}}</strong>
                    </span>
    
            </div>
        </div> 
<!-- Reference -->
     <div class="form-group{{ $errors->has('reference') ? ' has-error' : '' }}">
         <label class="col-md-4 control-label">Reference</label>
             <div class="col-md-6">
                 <input type="text" class="form-control" name='reference' description="reference" value="{{ old('reference') ? old('reference') : isset($leadsource->reference) ? $leadsource->reference : "" }}" placeholder="reference">
                 <span class="help-block">
                     <strong>{{ $errors->has('reference') ? $errors->first('reference') : ''}}</strong>
                     </span>
             </div>
     </div>
      