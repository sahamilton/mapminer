<div class="card" style="width: 80%;">

   <div class="card-body">
    <h5 class="card-title"><strong>Update Current Assignments</strong></h5>
    <p class="card-text">If your assignments are incomplete or incorrect, simply edit the list below by entering the correct branch numbers <em>(4 characters each)</em>, separated by commas and then click update.</p>

@method('put')
@csrf
<div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label"><strong>Branch Numbers:</strong></label>
	<div class="form-group">
		<textarea class="form-control col-md-8" name="branches"
		placeholder="branch ids separated by commas">{{ old('branches', isset($branches) ? $branches :'')}}</textarea>

		<input type="submit" 
		name="submit" 
		class="btn btn-success" 
		value="Update" />
		<span class="help-block">
			<strong>{{ $errors->has('branches') ? $errors->first('branches') : ''}}</strong>
		</span>
	</div>
	</div>
  </div>
</div>





</form>