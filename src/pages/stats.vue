<template>
  <div>
    <p class="caption" align="center">Statistics</p>

    <chart-stats
      chartType="bar"
      :labels="statsLabels('99%')"
      :datasets="statsDataset('99%')"
      :scales="statsScales('99%')"
      ref="chart99">
    </chart-stats>

    <chart-stats
      chartType="bar"
      :labels="statsLabels('90%')"
      :datasets="statsDataset('90%')"
      :scales="statsScales('90%')"
      ref="chart90">
    </chart-stats>

    <chart-stats
      chartType="bar"
      :labels="statsLabels('Total peers')"
      :datasets="statsDataset('Total peers')"
      :scales="statsScales('Total peers')"
      ref="totalPeers">
    </chart-stats>

    <chart-stats
      chartType="bar"
      :labels="statsLabels('Unique IPs in last 10 days')"
      :datasets="statsDataset('Unique IPs in last 10 days')"
      :scales="statsScales('Unique IPs in last 10 days')"
      ref="uniqueIPs">
    </chart-stats>

    <p align="center">
      <br/><q-btn
      color="primary"
      @click="$router.push('/')"
      label="Back to map"
    />
    </p>
  </div>
</template>

<script>
import ChartStats from '../components/ChartStats'
import randomColor from 'randomcolor'

export default {
  name: 'NodesStats',

  components: {
    ChartStats: ChartStats
  },

  mounted () {
    this.loadStats()
  },

  methods: {
    label: function (type) {
      let label = type
      if (type.indexOf('%') !== -1) {
        label = 'Nodes with >' + type + ' uptime'
      }
      return label
    },
    statsDataset: function (type) {
      let labels = []
      for (let label in this.$store.state.data.vars.statsData[type]) {
        labels.push(this.$store.state.data.vars.statsData[type][label])
      }
      return [
        {
          label: this.label(type),
          backgroundColor: randomColor(),
          data: labels
        }
      ]
    },
    statsScales: function (type) {
      return {
        yAxes: [{
          display: true,
          scaleLabel: {
            display: true,
            labelString: this.label(type)
          },
          ticks: {
            beginAtZero: true
          }
        }]
      }
    },
    statsLabels: function (type) {
      let labels = []
      for (let label in this.$store.state.data.vars.statsData[type]) {
        labels.push(window.moment(label, 'YYYY-MM-DD').format('D MMM YYYY'))
      }
      return labels
    },
    loadStats () {
      window.axios({
        url: '/stats.json',
        method: 'get',
        baseURL: this.$store.state.data.host
      })
        .then((response) => {
          this.$store.commit({type: 'data/setVar', key: 'statsData', value: response.data.data})
          this.$store.commit({type: 'data/copyTodayStatsData'})
          this.$nextTick(function () {
            this.$refs.chart99.updateChart()
            this.$refs.chart90.updateChart()
            this.$refs.totalPeers.updateChart()
            this.$refs.uniqueIPs.updateChart()
          })
          return true
        })
        .catch(function (error) {
          return 'Error! ' + error
        })
    }
  }
}
</script>

<style scoped>

</style>
