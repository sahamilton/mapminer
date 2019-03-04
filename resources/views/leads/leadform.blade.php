@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Web Leads</h2>
<div class="form-group">
<form method="post" name="editLead" action="{{route('leads.webleadsinsert')}}" >
{{csrf_field()}}
<!-- company -->
    <div class="form-group{{ $errors->has('weblead') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Paste Web Lead:</label>
           <div class="input-group input-group-lg ">
                <textarea class="form-control" name='weblead' description="weblead" >{{old('weblead')}}</textarea>
                <span class="help-block">
                    <strong>{{ $errors->has('weblead') ? $errors->first('weblead') : ''}}</strong>
                    </span>
            </div>
    </div>

		<div class="input-group input-group-lg ">
			<button type="submit" class="btn btn-success">Import Lead</button>
		</div>

</form>

</div>

</div>

@include('partials._scripts')
@endsection
