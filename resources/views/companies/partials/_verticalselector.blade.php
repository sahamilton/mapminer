<?php $parents=null;?>
		<div class="form-group{{ $errors->has('vertical)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Industry:</label>
         <div class="input-group input-group-lg ">
            <select required class="form-control" name='vertical'>

            @foreach ($filters as $vertical))
            @if($vertical->parent_id !=6 or $vertical->isLeaf())
            	<?php $parent = $vertical->getAncestors()->last();?>
	            	@if($parent->id!=$parents))
	            	<?php $parents=$parent->id;?>
	            	<option disabled value="{{$vertical->id}}">--------{{$parent->filter}}----------</option>
	            	@endif
            		<option @if(isset($company) && $company->vertical == $vertical->id) selected @endif value="{{$vertical->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$vertical->filter}}</option>

	            	
                    @endif
            @endforeach


            </select>
            <span class="help-block{{ $errors->has('vertical)') ? ' has-error' : '' }}">
                <strong>{{ $errors->has('vertical') ? $errors->first('vertical') : ''}}</strong>
                </span>
                </div>
    </div>