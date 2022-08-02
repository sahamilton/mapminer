 <div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @ray($title);
    <div class="row">
      <div class="col-sm form-inline">
        <x-form-select 
          name="view"
          wire:model="view"
          label="Chart:"
          :options='$views'
          />
        </div>
      </div>
    <div x-data="{
        series: {{json_encode($series)}},
        title: '{{$title}}',
        categories: {{json_encode($categories)}},
        init() {
            let chart = new ApexCharts(this.$refs.chart, this.options)
     
            chart.render()
     
            this.$watch('series', () => {
                chart.updateOptions(this.options)
            })
        },
        
        get options() {
            return {
     
                series: this.series,
                  chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: {
                      show: true
                    },
                    zoom: {
                      enabled: true
                    }
                  },
                  responsive: [{
                    breakpoint: 480,
                    options: {
                      legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                      }
                    }
                  },

              ],
              plotOptions: {
                bar: {
                  horizontal: false,
                  borderRadius: 10
                },
              },
              xaxis: {
                type: 'category',
                categories: this.categories,
              },
              legend: {
                position: 'right',
                offsetY: 40
              },
              fill: {
                opacity: 1
              },
              title: {
                text: this.title,
                align: 'center',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                  fontSize:  '14px',
                  fontWeight:  'bold',
                  fontFamily:  undefined,
                  color:  '#263238'
                },
}
          }
                        
        }
    }">
    <div x-ref="chart" class="bg-white rounded-lg p-8"></div>
</div>