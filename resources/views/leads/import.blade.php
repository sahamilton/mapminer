@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Add Propsects to the @if(isset($leadsource)) {{$leadsource->source}} @endif List</h2>
<h4>Steps to import prospects</h4>
    <ol>
        <li>First create your csv file of prospects from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to these fields:
            <ul>
            @foreach ($requiredFields as $field)
                <li style="color:red">{{$field}}</li>
            @endforeach
        </ul>
        </li>
        <li>Make sure there are no:
            <ul>
            <li>Commas</li>
            <li>Parentheses</li>
            <li>Quote marks</li>
            <li>Apostrophes</li>
        </ul>
         in your csv file. (<em>hint: use global find and replace</em>)</li>
        <li>Save the CSV file locally on your computer.</li>
        
        </ul>
        <li>Choose the prospects source</li>
        <li>Select the file and import</li>

    </ol>
<form method="post" name="createLead" action="{{route('leads.import')}}" enctype="multipart/form-data">
{{csrf_field()}}
<div class="form-group{{ $errors->has('lead_source_id)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Prospects Source</label>
    <div class="input-group input-group-lg ">
        <select class="form-control" name='additionaldata[lead_source_id]'>

        @foreach ($sources as $key=>$source))
        	<option @if(isset($leadsource) && $leadsource->id == $key) selected @endif value="{{$key}}">{{$source}}</option>

        @endforeach


        </select>
        <span class="help-block">
            <strong>{{ $errors->has('lead_source_id') ? $errors->first('lead_source_id') : ''}}</strong>
            </span>
    </div>
</div>


        <div class="form-group{{ $errors->has('upload') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label" for="field" >Upload File Location</label>
            <div class="input-group input-group-lg ">
                <input type="file" 
                class="form-control" 
                name='upload' id='upload' 
                description="upload" 
                value="{{  old('upload')}}">
                <span class="help-block">
                    <strong>{{ $errors->has('upload') ? $errors->first('upload') : ''}}</strong>
                </span>
            </div>

        </div>
	

<div class="form-group">
<input type="submit" class="btn btn-success" value="Add Prospects" />

<input type="hidden" name="type" value="leads" />

</div>
</form>



</div>

@include('partials._scripts')
@endsection
