

<div class="form-group">
<form method="post" name="editLead" action="{{route('leads.webleadsinsert')}}" >
{{csrf_field()}}
<!-- company -->
    <div class="form-group{{ $errors->has('weblead') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Or Paste Web Lead:</label>
           
                <textarea class="form-control" rows="10" name='weblead' description="weblead" >{{old('weblead')}}</textarea>
                <span class="help-block">
                    <strong>{{ $errors->has('weblead') ? $errors->first('weblead') : ''}}</strong>
                    </span>
            
    </div>
            <div class="form-group{{ $errors->has('lead_source_id)') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">Lead Source</label>
            <div class="col-md-8">
                <select  class="form-control" name='lead_source_id'>
    
                @foreach ($leadsources as $key=>$value))
                    <option value="{{$key}}">{{$value}}</option>
    
                @endforeach
    
    
                </select>
                <span class="help-block">
                    <strong>{{ $errors->has('lead_source_id') ? $errors->first('lead_source_id') : ''}}</strong>
                    </span>
            </div>
        </div>
        <div class="row">
		<div class="input-group input-group-lg ">
			<button type="submit" class="btn btn-success">Import Lead</button>
		</div>
    </div>

</form>

</div>

