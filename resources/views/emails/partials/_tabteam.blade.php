<p></p>
<!---- Tab team -->
<table id="sorttable" class="table table-striped">
<thead>
<tr>
<th></th>
<th>Recipiennt</th>
<th>Verticals</th>
<th>Roles</th>
<th>Email</th>
</tr>
</thead>

<tbody>
@foreach ($email->recipients as $team)
<tr>
<td><input type="checkbox" class='teamMember' checked name="rep[]" value="{{$team->id}}"></td>
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
<li>{{$role->name}}</li>
@endforeach
</ul>
</td>
<td>{{$team->userdetails->email}}</td>

</tr>
@endforeach
</tbody>
</table>
<form method="post" name="sendEmail" action="{{route('emails.send')}}" >
{{csrf_field()}}
<input type="hidden" name="id" value="{{$email->id}}" />
<input type="submit" name="Send" value="Send Email" class="btn btn-danger">
</form>
