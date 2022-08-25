
<!-- Report Name -->
<div class="form-group{{ $errors->has('report') ? ' has-error' : '' }}">
    
   
        <x-form-input class="input-group input-group-lg" required name="report" label="Report:" placeholder='Report name' />
    
</div>
<!-- Report Short description -->
<div class="form-group{{ $errors->has('report') ? ' has-error' : '' }}">
    <x-form-input class="input-group input-group-lg" required name="description" label="Short description:" placeholder='Short description' />
    
</div>



<!-- Report details -->
<div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
    <x-form-textarea name="details" id="summernote" required label="Details:" />

</div>

<!-- Report job -->
<div class="form-group{{ $errors->has('report') ? ' has-error' : '' }}">
    
   
        <x-form-input class="input-group input-group-lg" required name="job" label="Job:" placeholder='Job name' />
    
</div>

<!-- Report export -->
<div class="form-group{{ $errors->has('report') ? ' has-error' : '' }}">
    
   
        <x-form-input class="input-group input-group-lg" required name="export" label="Export:" placeholder='Export name' />
    
</div>
@php $options = ['Branch'=>'Branch', 'Campaign'=>'Campaign', 'Company'=>'Company', 'User'=>'User'];@endphp
<x-form-select required name="object" label="Object:" :options="$options" />
<!-- Period Selector -->
<div class="form-group  {{ $errors->has('period') ? ' has-error' : ''}}">
   
       <x-form-checkbox name='period' value=1 label="Period Selector:" />
       <x-form-checkbox name='public' value=1 label="Public Report:" />

</div>

