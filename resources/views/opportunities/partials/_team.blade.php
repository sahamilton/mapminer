
   <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Manager</th>
      <th>Leads</th>
      <th>Opportunities</th>
     
      <th class="tip" title="Activities in the past month">Period Activities</th>
      <th>Pipeline</th>
    <th>Won</th>
    <th>Booked</th>
    <th>Lost</th>
    </thead>
      <tbody>

        @foreach ($data['team']['team'] as $team)
       

        <tr>
          <td><a href="{{route('manager.dashboard',$team->id)}}">{{$team->fullName()}}</a></td>
          <td class="text-center">{{$data['team']['results'][$team->id]['leads']}}</td>
          <td class="text-center">{{$data['team']['results'][$team->id]['opportunities']}}</td>
          <td class="text-center">{{$data['team']['results'][$team->id]['activities']}}</td>
          <td class="text-right">${{number_format($data['team']['results'][$team->id]['pipeline'],0)}}</td>
          <td class="text-center">{{$data['team']['results'][$team->id]['won']}}</td>
          <td class="text-right">${{number_format($data['team']['results'][$team->id]['booked'],0)}}</td>
          <td class="text-center">{{$data['team']['results'][$team->id]['lost']}}</td>
        </tr>
        @endforeach
      </tbody>
    <tfoot>
      <span class="text text-danger">*</span>In past month
    </tfoot>

</table>