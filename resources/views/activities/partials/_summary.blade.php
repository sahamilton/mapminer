<div class= "row">
  <div class="col-sm-4">
    <table id='sorttable' class ='table table-bordered table-striped table-hover'>
      <thead>
        <th>Week Beginning</th>
        <th>Activities</th>
      </thead>
      <tbody>
        @foreach ($data['summary']['show'] as $dates)

          <tr>
            <td>{{$dates['date']}}</td>
            <td>{{$dates['count']}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

  </div>

<div class="col-sm-4" style="border:1 solid grey" class="float-right">
  <canvas id="ctx" width="400" height="400" ></canvas>
</div>
@if(isset($data['summary']))
<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>


var barChart = new Chart(ctx, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: [{!! $data['summary']['chart']['label'] !!}],

      datasets: [
          
          {
              label: "Weekly Activity",
              backgroundColor: "#ff0000",
              data:[{!! $data['summary']['chart']['data'] !!}],
              borderWidth: 1,
              fill:true,
          }
      ]
    },
},options = {
    scales: {
        xAxes: [{
            gridLines: {
                offsetGridLines: true
            }
        }]
    }
});
</script>

@endif
</div>