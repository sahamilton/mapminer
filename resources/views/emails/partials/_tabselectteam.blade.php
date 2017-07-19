<h2>Update the recipient list for this message</h2>

<form method="post" name="updateam" action="{{route('emails.updatelist')}}" >
{{csrf_field()}}
<div class="row">
<div class="col-md-6">
@include('emails.partials._verticals')
</div>
<div class="col-md-6">
@include('emails.partials._roles')
</div>
</div>
<input type="submit" class="btn btn-success" value="Update Participants" />
<input type="hidden" name="id" value="{{$email->id}}" />
</form>
@include('partials._verticalsscript')