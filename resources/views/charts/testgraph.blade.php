<!doctype html>
<html>

<head>
    <title>Scatter Chart</title>
     <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
     <script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
   
</head>
@php

$data =['Branch 20'=>['x'=>'100','y'=>'120'],
'Branch 40'=>['x'=>'80','y'=>'20']];
@endphp

<body>
    <div style="width:50%">
        <div>
            <canvas id="canvas" height="450" width="600"></canvas>
        </div>
    </div>
    <button id="randomizeData">Randomize Data</button>
    <script>
    var randomScalingFactor = function() {
        return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
    };
    var randomColor = function(opacity) {
        return 'rgba(' + Math.round(Math.random() * 255) + ',' + Math.round(Math.random() * 255) + ',' + Math.round(Math.random() * 255) + ',' + (opacity || '.3') + ')';
    };

    var scatterChartData = {
    	data:{
	      label: 'My First dataset',
	      strokeColor: '#F16220',
	      pointColor: '#F16220',
	      pointStrokeColor: '#fff',
	      data: [
	        { x: 19, y: 65 }, 
	        { x: 27, y: 59 }, 
	        { x: 28, y: 69 }, 
	        { x: 40, y: 81 },
	        { x: 48, y: 56 }
	      ]
	    }

    };

   

    console.log(scatterChartData);

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myScatter = new Chart(ctx,
    	{

    type: 'scatter',
    data: {
        datasets: [{
            label: 'Scatter Dataset',
            data: [{
                x: 10,
                y: 0
            }, {
                x: 0,
                y: 20
            }, {
                x: 30,
                y: 5
            }]
        }]
    },
    options: {
        scales: {
            xAxes: [{
                type: 'linear',
                position: 'bottom'
            }]
        }
    }
});

    
    </script>
</body>

</html>