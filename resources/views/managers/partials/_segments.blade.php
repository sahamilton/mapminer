<h4>Company Locations By Segment</h4>
<table id ='sorttable4' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    <th>Company</th>
    <th>Segment</th>
    <th>Location Count</th>
  </thead>
  <tbody>
    @foreach($data['segments'] as $segment)
      <tr>
        <td>{{$segment->companyname}}</td>
        <td>{{$segment->filter ? $segment->filter : 'Not Assigned'}}</td>
        <td>{{$segment->count}}</td>
      </tr>
    @endforeach
  </tbody>
</table>