
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
    <label class=" control-label">Details:</label>
    <div class="input-group input-group-lg">
        <textarea 
        id="summernote" 
        class="form-control summernote" 
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

<!-- Period Selector -->
<div class="form-group  {{ $errors->has('period') ? ' has-error' : ''}}">
    <label class=" control-label">Period Selector:
    <div class="input-group checkbox inline input-group-sm">
        <input 
        type="checkbox" 
        class="form-control" 
        name='period' 
        @if(old('period',isset($report) && $report->period))
            checked
       @endif
        value="1"
        title="Include Period Selector"
        period="period" 
        ></label>
        <span class="help-block">
            <strong>{{ $errors->has('period') ? $errors->first('period') : ''}}</strong>
        </span>
    </div>
</div>

<!-- Public  -->
<div class="form-group  {{ $errors->has('public') ? ' has-error' : ''}}">
    <label class=" control-label">Public Report:
    <div class="input-group checkbox inline input-group-sm">
        <input 
        type="checkbox" 
        class="form-control" 
        name='public' 
        @if(old('public',isset($report) && $report->public))
            checked
       @endif
        value="1"
        title="Include public Selector"
        public="public" 
        ></label>
        <span class="help-block">
            <strong>{{ $errors->has('public') ? $errors->first('public') : ''}}</strong>
        </span>
    </div>
</div>
