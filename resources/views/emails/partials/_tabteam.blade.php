@if(! $email->sent)
<p><a href="{{route('emails.show',$email->id)}}">Refresh list</a></p>
@endif

<!---- Tab team -->
<table id="sorttable" class="table table-striped">
<thead>
<tr>
<th></th>
<th>Recipient</th>
<th>Verticals</th>
<th>Roles</th>
<th>Email</th>
</tr>
</thead>

<tbody>
@foreach ($email->recipients as $team)
<tr>
<td><input type="checkbox" class='recipient' checked name="rep[]" value="{{$team->id}}"></td>
<td>{{$team->fullName()}}</td>
<td>
<ul>
@foreach ($team->industryfocus as $vertical)
<li>{{$vertical->filter}}</li>
@endforeach
</ul>
</td>
<td>
<ul>
@foreach ($team->userdetails->roles as $role)
<li>{{$role->displayName}}</li>
@endforeach
</ul>
</td>
<td>{{$team->userdetails->email}}</td>

</tr>
@endforeach
</tbody>
</table>
@if(! $email->sent)
<form method="post" name="sendEmail" action="{{route('emails.send')}}" >
{{csrf_field()}}
<input type="hidden" name="id" value="{{$email->id}}" />
<p>Send a test: <input type="checkbox" checked name="test" value='1' /></p>
<input type="submit" name="Send" value="Send Email" class="btn btn-danger">
</form>
@endif
