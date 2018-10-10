<h4>Active Watchers</h4>

<a href="{{route('company.watchexport')}}?id='{{strip_tags($data['accountstring'])}}'" title="Download {{$data['title']}}watch list as a CSV / Excel file"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download Watch List</a>

<table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>

    <th>Users</th>
    <th>Watching Locations</th>

  </thead>
  <tbody

    @foreach($data['watching'] as $watchers)

      <tr>
        <td><a href="/watcher/{{$watchers->user_id}}" >{{$watchers->name}}</a></td>
        <td>{{$watchers->watching}}</td>
      </tr>

    @endforeach
  </tbody>
</table>
