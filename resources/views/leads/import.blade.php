@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Add Leads to the @if(isset($leadsource)) {{$leadsource->source}} @endif List</h2>

<form method="post" name="createLead" action="{{route('leads.import')}}" enctype="multipart/form-data">
{{csrf_field()}}
<div class="form-group{{ $errors->has('lead_source_id)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Leads Source</label>
        <div class="col-md-6">
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
@include('leadsource.partials._addleadform')
		

<div class="form-group">
<input type="submit" class="btn btn-success" value="Add Leads" />

<input type="hidden" name="type" value="leads" />

</div>
</form>



</div>

@include('partials._scripts')
@endsection
