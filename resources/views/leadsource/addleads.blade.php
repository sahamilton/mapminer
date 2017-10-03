@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Add Prospects to the {{$leadsource->source}} List</h2>

<form method="post" name="createLead" action="{{route('leadsource.addleads',$leadsource->id)}}" enctype="multipart/form-data">
{{csrf_field()}}

@include('leadsource.partials._addleadform')

<div class="form-group">
<input type="submit" class="btn btn-success" value="Add Prospects" />
<input type="hidden" name="additionaldata[lead_source_id]" value="{{$leadsource->id}}" />

<input type="hidden" name="type" value="leads" />

</div>
</form>



</div>

@include('partials._scripts')
@endsection
