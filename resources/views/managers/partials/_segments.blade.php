<h4>Company Locations By Industry & Segment</h4>
<table id ='sorttable4' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    <th>Company</th>
    <th>Industry</th>
    <th>Segment</th>
    <th>Location Count</th>
  </thead>
  <tbody>
    @foreach($data['segments'] as $segment)
      <tr>
        <td>{{$segment->companyname}}</td>
        <td>{{$segment->industry}}</td>
        <td>{{$segment->segment ? $segment->segment : 'Not Assigned'}}</td>
        <td>{{$segment->count}}</td>
      </tr>
    @endforeach
  </tbody>
</table>