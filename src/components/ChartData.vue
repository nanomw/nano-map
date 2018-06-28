<template>
  <div>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
export default {
  name: 'ChartData',
  props: {
    chartType: {
      type: String,
      default () { return 'pie' },
      required: true
    },
    datasets: {
      type: Array,
      default () { return [] },
      required: true
    },
    labels: {
      type: Array,
      default () { return [] },
      required: true
    },
    colors: {
      type: Array,
      default () { return [] },
      required: true
    }
  },
  data () {
    return {
      chart: null,
      height: '200px'
    }
  },
  methods: {
    updateChart: function () {
      let ctx = this.$refs.chart.getContext('2d')
      if (this.chart) {
        this.chart.destroy()
      }
      this.chart = new window.Chart(ctx, {
        type: this.chartType,
        data: {
          labels: this.labels,
          datasets: [{
            data: this.datasets,
            backgroundColor: this.colors
          }]
        },
        options: {
          responsive: true,
          legend: {
            display: false
          }
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
