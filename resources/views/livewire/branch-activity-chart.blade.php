 <div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div x-data="{
        labels: {{json_encode($labels)}},
        values: {{json_encode($values)}},
        init() {
            let chart = new ApexCharts(this.$refs.chart, this.options)
     
            chart.render()
     
            this.$watch('values', () => {
                chart.updateOptions(this.options)
            })
        },
        get options() {
            return {
                chart: { type: 'bar', toolbar: false },
                tooltip: {
                    marker: false,
                    y: {
                        formatter(number) {
                            return '$'+number
                        }
                    }
                },
                xaxis: { categories: this.labels },
                series: [{
                    name: 'Sales',
                    data: this.values,
                }],
            }
        }
    }">
    <div x-ref="chart" class="bg-white rounded-lg p-8"></div>
</div>