@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Sales Campaign</h2>
<div class="form-group col-md-6">
<form method="post" name="createStep" action="{{route('campaigns.update', $campaign->id)}}">
@csrf
@method('put')

@include('campaigns.partials._form')

<input type="submit" class="btn btn-success" value="Edit Sales Campaign" />
</form>

</div>

</div>

@include('campaigns.partials._scripts')
@endsection
