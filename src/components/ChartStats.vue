<template>
  <div>
    <canvas ref="chart" :height="height"></canvas>
  </div>
</template>

<script>
export default {
  name: 'ChartStats',
  props: {
    chartType: {
      type: String,
      default () { return 'bar' },
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
    scales: {
      type: Object,
      default () { return {} },
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
          datasets: this.datasets
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          tooltips: {
            mode: 'index',
            intersect: false
          },
          scales: this.scales
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
