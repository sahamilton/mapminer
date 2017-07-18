<h2>Update the recipient list for this message</h2>

<form method="post" name="updateam" action="{{route('emails.updatelist')}}" >
{{csrf_field()}}
@include('emails.partials._verticals')
<input type="submit" class="btn btn-success" value="Update Participants" />
<input type="hidden" name="id" value="{{$email->id}}" />
</form>
@include('partials._verticalsscript')