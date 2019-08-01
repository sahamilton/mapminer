
<!-- Report Name -->
<div class="form-group{{ $errors->has('report') ? ' has-error' : '' }}">
    <label class=" control-label">Report:</label>
    <div class="input-group input-group-lg">
        <input 
        type="text" 
        class="form-control" 
        name='report' 
        required
        export="report" 
        value="{{ old('report', isset($report) ? $report->report :'' ) }}" 
        placeholder="report name">
        <span class="help-block">
            <strong>{{ $errors->has('report') ? $errors->first('report') : ''}}</strong>
        </span>
    </div>
</div>

<!-- Report Short description -->
<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class=" control-label">Short description:</label>
    <div class="input-group input-group-lg">
        <input 
        type="text" 
        class="form-control" 
        name='description' 
        required
        description="description" 
        value="{{ old('description', isset($report) ? $report->description :'' ) }}" 
        placeholder="short description">
        <span class="help-block">
            <strong>{{ $errors->has('description') ? $errors->first('description') : ''}}</strong>
        </span>
    </div>
</div>

<!-- Report details -->
<div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
    <label class=" control-label">details:</label>
    <div class="input-group input-group-lg">
        <textarea  
        class="form-control" 
        name='details' 
        required
        details="details">{{ old('details', isset($report) ? $report->details :'' ) }}
        </textarea>
       
        <span class="help-block">
            <strong>{{ $errors->has('details') ? $errors->first('details') : ''}}</strong>
        </span>
    </div>
</div>

<!-- Report job -->
<div class="form-group{{ $errors->has('job') ? ' has-error' : '' }}">
    <label class=" control-label">Job:</label>
    <div class="input-group input-group-lg">
        <input 
        type="text" 
        class="form-control" 
        name='job' 
        required
        job="job" 
        value="{{ old('job', isset($report) ? $report->job :'' ) }}" 
        placeholder="job">
        <span class="help-block">
            <strong>{{ $errors->has('job') ? $errors->first('job') : ''}}</strong>
        </span>
    </div>
</div>

<!-- Report export -->
<div class="form-group{{ $errors->has('export') ? ' has-error' : '' }}">
    <label class=" control-label">Export:</label>
    <div class="input-group input-group-lg">
        <input 
        type="text" 
        class="form-control" 
        name='export' 
        required
        export="export" 
        value="{{ old('export', isset($report) ? $report->export :'' ) }}" 
        placeholder="export">
        <span class="help-block">
            <strong>{{ $errors->has('export') ? $errors->first('export') : ''}}</strong>
        </span>
    </div>
</div>