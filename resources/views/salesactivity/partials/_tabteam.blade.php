<p><a href="{{route('campaign.announce',$activity->id)}}">Refresh list</a></p>
<!---- Tab team -->
<table id="sorttable" class="table table-striped">
<thead>
<tr>
<th></th>
<th>Sales Rep</th>
<th>Verticals</th>
<th>Manager</th>
<th>Location</th>
<th>Email</th>
</tr>
</thead>

<tbody>
@foreach ($salesteam as $team)
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
@if($team->reportsTo)
	{{$team->reportsTo->fullName()}}
@endif
</td>
<td>{{$team->city}}, {{$team->state}}</td>
<td>{{$team->userdetails->email}}</td>

</tr>
@endforeach
</tbody>
</table>
