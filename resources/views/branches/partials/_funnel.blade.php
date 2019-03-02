<table id='sorttable3' class ='table table-bordered table-striped table-hover'>
  <thead>
    <th>Week Beginning</th>
    <th>Expected Value</th>
  </thead>
  <tbody>
    @foreach ($data['funnel'] as $dates)

      <tr>
        <td>{{$dates['yearweek']}}</td>
        <td class="text-right">${{number_format($dates['funnel'],2)}}</td>
      </tr>
    @endforeach
  </tbody>
  <tfoot>
    <td class="text-right" colspan='2'>${{number_format($data['funnel']->sum('funnel'),2)}}</td>
  </tfoot>
</table>


