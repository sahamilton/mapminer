<div class= "row">
  <div class="col-sm-4">
    <table id='sorttable' class ='table table-bordered table-striped table-hover'>
      <thead>
        <th>Week Beginning</th>
        <th>Activities</th>
      </thead>
      <tbody>
        @if(isset($data['summary']['show']))
          @foreach ($data['summary']['show'] as $dates)

            <tr>
              <td>{{$dates['date']}}</td>
              <td>{{$dates['count']}}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>

  </div>
<div class="col-sm-4" style="border:1 solid grey" class="float-right">
  <canvas id="ctx" width="400" height="400" ></canvas>
</div>
@include('activities.partials._activitiesstackedchart')