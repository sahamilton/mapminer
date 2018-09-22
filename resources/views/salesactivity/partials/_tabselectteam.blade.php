<h2>Update the sales team to participate in this program</h2>
(<i>Note this does not change the focus of the sales campaign</i>)
<form method="post" name="updateam" action="{{route('salesactivity.modifyteam')}}" >
{{csrf_field()}}
@include('salesactivity.partials._verticals')
<input type="submit" class="btn btn-success" value="Update Participants" />
<input type="hidden" name="campaign_id" value="{{$activity->id}}" />
</form>
@include('partials._verticalsscript')