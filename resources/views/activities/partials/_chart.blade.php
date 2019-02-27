<div class= "row">
  <div class="col-sm-4">


</div>
<div class="col-sm-4" style="border:1 solid grey" class="float-right">
<canvas id="ctx" width="400" height="400" ></canvas>
  </div>
</div>
@if(isset($data))
<script type="text/javascript" 
src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>


var barChart = new Chart(ctx, 
{

    type: 'bar',

    resize:true,

    data:{
      labels: ['201905','201906','201907','201908','201909'],

      datasets: [{
          
            label: 'Branch 312',    
            data:[0,7,34,34,0],
            backgroundColor: [
              '#009933',
              '#009933',
              '#009933',
              '#009933',
              '#009933',            

              ],
          }, {
            label: 'Branch 2147',
            data:[1,3,5,48,10],
            backgroundColor: [
              '#933900',
              '#933900',
              '#933900',
              '#933900',
              '#933900',
              '#933900'
              ],
         }, {
          label: 'Branch 2468',
          data:[1,8,4,1,1],
            backgroundColor: [
              '#330099',
              '#330099',
              '#330099',
              '#330099',
              '#330099'
              ],
         }, {
          label: 'Branch 3501',
          data:[40,16,20,180,1],
            backgroundColor: [
               '#303099',
               '#303090',
               '#303099',
               '#303090',
               '#303099'
              ],
        }, {
          label: 'Branch 35291',
          data:[8,91,214,1,1],
            backgroundColor: [
              '#330990',
               '#330990',
                '#330990',
                 '#330990',
                  '#330990'
              ],
      }],
    },
 
});
</script>
@endif