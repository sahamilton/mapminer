@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Contacts</h2>
First create your csv file of projects from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to:
            <ol>
            @foreach ($requiredFields as $field)
                <li style="color:red">{{$field}}</li>
            @endforeach
        </ol>


<form name="contactsimport" method="post" action="{{route('contacts.import')}}" 
enctype="multipart/form-data">
{{csrf_field()}}

<form method="post" 
    action ="{{route('contacts.import')}}" 
    enctype="multipart/form-data" 
    name = "importLocations">
{{csrf_field()}}

<!---- Description -->

    <div class="form-group{{ $errors->has('description)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Import Description:</label>
       
        <div class="form-group">
            <textarea name="description" 
            required
            class="form-control" 
            rows="5" 
            placeholder="Describe the import, source etc"></textarea>
        </div>
        <span class="help-block">
            <strong>{{ $errors->has('description') ? $errors->first('description') : ''}}</strong>
        </span>
        
    </div>
<!-----/ description-->
    <!-- Lead Source -->

    <div class="form-group{{ $errors->has('leadsource)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">LeadSource</label>
        <div class="input-group input-group-lg">
            <select  class="form-control" name='lead_source_id'>
                <option ></option>
                @foreach ($leadsources as $key=>$value))
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('leadsource') ? $errors->first('leadsource') : ''}}</strong>
            </span>
        </div>
    </div>
    <div class="form-inline{{ $errors->has('newleadsource)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label radio-inline">Create New LeadSource?:</label>
        <div class="form-group">
            <input 
                type="checkbox" 
                name="newleadsource" 
                class="form-control"
                value=1 
                checked />
            <input 
                type="text" 
                placeholder="Enter a name for leadsource ..." 
                name="newleadsourcename" 
                class="form-control" />
            <span class="help-block">
                <strong>{{ $errors->has('leadsource') ? $errors->first('leadsource') : ''}}</strong>
            </span>
        </div>
    </div>
    <!---------- Servicelines   ---------------->
    <div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Servicelines</label>
        <div class="input-group input-group-lg">
            <select multiple required class="form-control" name='serviceline[]'>
                @foreach ($servicelines as $key=>$value))
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}</strong>
            </span>
        </div>
    </div>
  
<legend>File Location:</legend>

<div class="form-group{{ $errors->has('upload') ? ' has-error' : '' }}">
     <label class="col-md-2 control-label">Upload File Location</label>
     	<div class="input-group input-group-lg ">
         <input required type="file" class="form-control" name='upload' id='upload' description="upload" 
         value="{{ old('upload')}}">
         <strong>{!! $errors->first('upload', '<p class="help-block">:message</p>') !!}</strong>
     </div>
 </div>


 <input type="submit" name="submit" class="btn btn-info" value="Import">


</form>

</div>

@endsection