`<template>
  <div>

    <q-window-resize-observable @resize="onResize" />

    <span
      class="fixed-top-right"
      :style="{top: $store.state.data.vars.headerHeight + 'px', zIndex: 1000}">

      <q-btn
        flat
        style="color: green; padding-bottom: 0px; margin-bottom: 0px;"
        :label="showNodesInfo"
        @click="showSelectedInfo"/><br/>

      <q-btn
        flat
        style="color: blue; padding-top: 0px;"
        :label="selectionInfo"
        @click="settingsOpened = true"/>
    </span>

    <div
      id="nodesMap"
      :style="{height: containerHeight}">
    </div>

    <!---------------------------------------------------->
    <!--------------------Countries----------------------->
    <!---------------------------------------------------->

    <q-modal
      v-model="countriesOpened"
      :content-css="{padding: '10px', minWidth: '50vw'}">

      <chart-data
        chartType="pie"
        :labels="this.countriesTableData.map(val => val.name)"
        :datasets="this.countriesTableData.map(val => val.nodes)"
        :colors="this.countriesTableData.map(val => val.color)"
        ref="countriesChart">
      </chart-data>

      <q-table
        title="Countries"
        :data="countriesTableData"
        :filter="filterCountries"
        :columns="countriesColumns"
        :pagination.sync="pagination1"
        row-key="name"
      >
        <template
          slot="top-left"
          slot-scope="props">

          <q-search
            hide-underline
            color="secondary"
            v-model="filterCountries"
            class="col-6"
          />

        </template>

        <q-tr slot="header" slot-scope="props">
          <q-th
            v-for="col in props.cols"
            :key="col.name"
            :props="props">
            {{ col.label }}
          </q-th>
        </q-tr>

        <template slot="body" slot-scope="props">

          <q-tr :props="props">
            <q-td key="name" :props="props" style="max-width: 50%;">
              <q-checkbox
                color="primary"
                v-model="props.expand"
                checked-icon="remove"
                unchecked-icon="add"
                class="q-mr-md" />
              <span :style="{marginRight: '10px', backgroundColor: props.row.color, color: props.row.color}">_______</span>
              {{ props.row.name }}
            </q-td>
            <q-td key="nodes" :props="props" style="max-width: 25%;">
              {{ props.row.nodes }}
            </q-td>
            <q-td key="percentage" :props="props" style="max-width: 25%;">
              {{ props.row.percentage }}
            </q-td>
          </q-tr>

          <q-tr v-show="props.expand" :props="props">
            <q-td colspan="100%">

              <q-table
                :data="getStatesTableData(props.row.name)"
                :columns="statesColumns"
                :pagination.sync="pagination2"
                row-key="name"
              >
                <template slot="body" slot-scope="props">
                  <q-tr :props="props">
                    <q-td key="name" :props="props" style="max-width: 50%;">
                      {{ props.row.name.substring(0, 32) }}
                    </q-td>
                    <q-td key="nodes" :props="props" style="max-width: 25%;">
                      {{ props.row.nodes }}
                    </q-td>
                    <q-td key="percentage" :props="props" style="max-width: 25%;">
                      {{ props.row.percentage }}
                    </q-td>
                  </q-tr>
                </template>
              </q-table>

              <q-table
                :data="getIspCountryTableData(props.row.name)"
                :columns="ispCountryColumns"
                :pagination.sync="pagination3"
                row-key="name"
              >
                <template slot="body" slot-scope="props">
                  <q-tr :props="props">
                    <q-td key="name" :props="props" style="max-width: 50%;">
                      <a :href="'https://www.google.com/search?q=' + props.row.name" target="_blank">{{ props.row.name.substring(0, 32) }}</a>
                    </q-td>
                    <q-td key="nodes" :props="props" style="max-width: 25%;">
                      {{ props.row.nodes }}
                    </q-td>
                    <q-td key="percentage" :props="props" style="max-width: 25%;">
                      {{ props.row.percentage }}
                    </q-td>
                  </q-tr>
                </template>
              </q-table>

            </q-td>
          </q-tr>
        </template>
      </q-table>

      <p align="center"><br/><q-btn
        color="primary"
        @click="countriesOpened = false"
        label="Close"
      /></p>

    </q-modal>

    <!---------------------------------------------------->
    <!--------------------Providers----------------------->
    <!---------------------------------------------------->

    <q-modal
      v-model="ispsOpened"
      :content-css="{padding: '10px', minWidth: '50vw'}">

      <chart-data
        chartType="pie"
        :labels="this.ispsTableData.map(val => val.name)"
        :datasets="this.ispsTableData.map(val => val.nodes)"
        :colors="this.ispsTableData.map(val => val.color)"
        ref="ispsChart">
      </chart-data>

      <q-table
        title="Providers"
        :data="ispsTableData"
        :filter="filterIsps"
        :columns="ispsColumns"
        :pagination.sync="pagination"
        selection="multiple"
        :selected.sync="selectedIsps"
        row-key="name"
      >

        <template slot="top-left" slot-scope="props">
          <q-search
            hide-underline
            color="secondary"
            v-model="filterIsps"
            class="col-6"
          />
        </template>

        <template slot="top-selection" slot-scope="props">
          <q-btn
            color="secondary"
            label="Show selected on map"
            @click="showSelectedIsps" />
          <q-btn
            color="white"
            label=""
            flat/>
          <q-btn
            color="info"
            label="Show all"
            @click="showAll"/>
        </template>

        <q-tr slot="header" slot-scope="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
        </q-tr>

        <template slot="body" slot-scope="props">
          <q-tr :props="props">
            <q-td key="name" :props="props" style="max-width: 10%;">
              <q-checkbox color="primary" v-model="props.selected" />
              <span :style="{marginRight: '10px', backgroundColor: props.row.color, color: props.row.color}">____</span>
              <a :href="'https://www.google.com/search?q=' + props.row.name" target="_blank">{{ props.row.name.substring(0, 30) }}</a>
            </q-td>
            <q-td key="nodes" :props="props" style="max-width: 10%; width: 20px;">{{ props.row.nodes }}</q-td>
            <q-td key="percentage" :props="props" style="max-width: 10%;">{{ props.row.percentage }}</q-td>
          </q-tr>
        </template>

      </q-table>

      <p align="center"><br/><q-btn
        color="primary"
        @click="ispsOpened = false"
        label="Close"
      /></p>

    </q-modal>

    <!---------------------------------------------------->
    <!--------------------Nodes list---------------------->
    <!---------------------------------------------------->
    <q-modal v-model="listOpened" :content-css="{padding: '10px', minWidth: '50vw'}">

      <q-table
        title="Nodes list"
        :data="listTableData"
        :filter="filter"
        :columns="listColumns"
        :pagination.sync="pagination"
        row-key="rank"
      >

        <template slot="top-left" slot-scope="props">
          <q-search
            hide-underline
            color="secondary"
            v-model="filter"
            class="col-6"
          />
        </template>

        <q-tr slot="header" slot-scope="props">
          <q-th
            v-for="col in props.cols"
            :key="col.name"
            :props="props">
            {{ col.label }}
          </q-th>
        </q-tr>

        <template slot="body" slot-scope="props">
          <q-tr :props="props">
            <q-td key="rank" :props="props">{{ props.row.rank }}</q-td>
            <q-td key="ip" :props="props">
              <a :href="createHref(props.row.ip, '')" target="_blank" v-show="props.row.wi === 'Yes'">{{ props.row.ip }}</a>
              <span v-show="props.row.wi === 'No'">{{ props.row.ip }}</span>
            </q-td>
            <q-td key="host" :props="props">
              <a :href="createHref(props.row.host, props.row.whs)" target="_blank" v-show="props.row.wh === 'Yes'">{{ props.row.host }}</a>
              <span v-show="props.row.wh === 'No'">{{ props.row.host }}</span>
            </q-td>
            <q-td key="online" :props="props">
              <i class="material-icons" :style="{color: onlineColor(props.row.lastOnline)}">fiber_manual_record</i>{{ onlineTextTable(props.row.lastOnline) }}
            </q-td>
            <q-td key="onlinesince" :props="props">{{ onlineSince(props.row.firstOnline) }}</q-td>
            <q-td key="percentage" :props="props">{{ props.row.percentage }}%</q-td>
            <q-td key="website" :props="props">{{ props.row.website }}</q-td>
            <q-td key="isp" :props="props"><a :href="'https://www.google.com/search?q=' + props.row.isp" target="_blank">{{ props.row.isp }}</a></q-td>
            <q-td key="country" :props="props">{{ props.row.country }}</q-td>
            <q-td key="state" :props="props">{{ props.row.state }}</q-td>
            <q-td key="city" :props="props">{{ props.row.city }}</q-td>
          </q-tr>
        </template>

      </q-table>

      <p align="center"><br/><q-btn
        color="primary"
        @click="listOpened = false"
        label="Close"
      /></p>

    </q-modal>

    <!---------------------------------------------------->
    <!--------------------Settings------------------------>
    <!---------------------------------------------------->
    <q-modal v-model="settingsOpened" :content-css="{padding: '10px', minWidth: '50vw'}">
      <p class="caption">Showing nodes that have been online at least</p>
      <q-select
        v-model="$store.state.data.vars.minimumPercentage"
        radio
        :options="minimumPercentageOptions"
      />
      <p class="caption">since at least</p>
      <q-select
        v-model="$store.state.data.vars.minimumDays"
        radio
        :options="minimumDaysOptions"
      />
      <p class="caption">days ago.</p>
      <p align="center"><br/><q-btn
        color="primary"
        @click="settingsOpened = false"
        label="Apply"
      /></p>

    </q-modal>

    <!---------------------------------------------------->
    <!--------------------Selection----------------------->
    <!---------------------------------------------------->
    <q-modal v-model="selectionOpened" :content-css="{padding: '10px', minWidth: '50vw'}">
      <p class="caption" align="center">Currently showing {{showNodesInfo}} of {{totalNodes}} ({{onlineNodes}}) nodes.</p>
      <p align="center">
        <br/><q-btn
        color="primary"
        @click="selectionOpened = false"
        label="Continue showing selected"
      /><br/><br/>
        <q-btn color="info" label="Show all" @click="showAll"/>
      </p>
    </q-modal>

  </div>
</template>

<script>
import ChartData from '../components/ChartData'
import randomColor from 'randomcolor'

export default {
  name: 'NodesMap',
  components: {
    ChartData: ChartData
  },
  data () {
    return {
      nodes: {},
      onlineNodes: 0,
      selectedTotalNodes: 0,
      selectedOnlineNodes: 0,
      interval: null,
      nodesFilter: null,
      map: null,
      markers: null,
      ispsTableData: [],
      listTableData: [],
      isps: {},
      ispsCountry: {},
      filter: '',
      filterCountries: '',
      filterIsps: '',
      selectedIsps: [],
      ispsSelected: false,
      selectedCountries: [],
      pagination: {rowsPerPage: 20},
      pagination1: {rowsPerPage: 20},
      pagination2: {rowsPerPage: 20},
      pagination3: {rowsPerPage: 20},
      countries: {},
      statesColumns:
        [
          {
            name: 'name',
            required: true,
            label: 'State/province',
            align: 'left',
            field: 'name',
            sortable: false
          },
          {
            name: 'nodes',
            required: true,
            label: 'Nodes',
            field: 'nodes',
            sortable: false
          },
          {
            name: 'percentage',
            required: true,
            label: '%',
            field: 'percentage',
            sortable: false
          }
        ],
      ispCountryColumns:
        [
          {
            name: 'name',
            required: true,
            label: 'Provider',
            align: 'left',
            field: 'name',
            sortable: false
          },
          {
            name: 'nodes',
            required: true,
            label: 'Nodes',
            field: 'nodes',
            sortable: false
          },
          {
            name: 'percentage',
            required: true,
            label: '%',
            field: 'percentage',
            sortable: false
          }
        ],
      listColumns:
        [
          {
            name: 'rank',
            required: true,
            label: 'Rank',
            align: 'left',
            field: 'rank',
            sortable: true
          },
          {
            name: 'ip',
            required: true,
            label: 'IP address',
            align: 'left',
            field: 'ip',
            sortable: true
          },
          {
            name: 'host',
            required: true,
            label: 'Hostname',
            align: 'left',
            field: 'host',
            sortable: true
          },
          {
            name: 'online',
            required: true,
            label: 'Online',
            align: 'left',
            field: 'lastOnline',
            sortable: true
          },
          {
            name: 'onlinesince',
            required: true,
            label: 'Online since',
            align: 'left',
            field: 'firstOnline',
            sortable: true
          },
          {
            name: 'percentage',
            required: true,
            label: 'Uptime',
            align: 'left',
            field: 'percentage',
            sortable: true
          },
          {
            name: 'website',
            required: true,
            label: 'Website',
            align: 'left',
            field: 'website',
            sortable: true
          },
          {
            name: 'isp',
            required: true,
            label: 'Provider',
            align: 'left',
            field: 'isp',
            sortable: true
          },
          {
            name: 'country',
            required: true,
            label: 'Country',
            align: 'left',
            field: 'country',
            sortable: true
          },
          {
            name: 'state',
            required: true,
            label: 'State/province',
            align: 'left',
            field: 'state',
            sortable: true
          },
          {
            name: 'city',
            required: true,
            label: 'City',
            align: 'left',
            field: 'city',
            sortable: true
          }
        ]
    }
  },

  computed: {
    showNodesInfo: function () {
      if (!this.ispsSelected) {
        return this.totalNodes + ' (' + this.onlineNodes + ')'
      } else {
        return this.selectedTotalNodes + ' (' + this.selectedOnlineNodes + ')'
      }
    },
    minimumPercentageOptions: {
      get: function () {
        let minimumPercentageOptions = []
        for (let i = 99; i >= 90; i--) {
          minimumPercentageOptions.push({
            label: i + '%',
            value: i
          })
        }
        return minimumPercentageOptions
      }
    },
    minimumDaysOptions: {
      get: function () {
        let minimumDaysOptions = []
        for (let i = 7; i <= 30; i++) {
          minimumDaysOptions.push({
            label: i.toString(),
            value: i
          })
        }
        return minimumDaysOptions
      }
    },
    containerHeight: {
      get: function () {
        return this.$store.state.data.vars.containerHeight + 'px'
      }
    },
    selectionInfo: {
      get: function () {
        return this.$store.state.data.vars.minimumPercentage + '% ' + this.$store.state.data.vars.minimumDays + 'd+'
      }
    },
    settingsOpened: {
      get: function () {
        return this.$store.state.data.vars.settingsOpened
      },
      set: function (val) {
        this.$store.commit({type: 'data/setVar', key: 'settingsOpened', value: val})
        this.drawMap()
      }
    },
    ispsOpened: {
      get: function () {
        return this.$store.state.data.vars.ispsOpened
      },
      set: function (val) {
        this.$store.commit({type: 'data/setVar', key: 'ispsOpened', value: val})
      }
    },
    countriesOpened: {
      get: function () {
        return this.$store.state.data.vars.countriesOpened
      },
      set: function (val) {
        this.$store.commit({type: 'data/setVar', key: 'countriesOpened', value: val})
      }
    },
    listOpened: {
      get: function () {
        return this.$store.state.data.vars.listOpened
      },
      set: function (val) {
        this.$store.commit({type: 'data/setVar', key: 'listOpened', value: val})
      }
    },
    selectionOpened: {
      get: function () {
        return this.$store.state.data.vars.selectionOpened
      },
      set: function (val) {
        this.$store.commit({type: 'data/setVar', key: 'selectionOpened', value: val})
      }
    },
    countriesColumns: function () {
      return [
        {
          name: 'name',
          required: true,
          label: 'Country (' + this.totalNodes + ' nodes total)',
          align: 'left',
          field: 'name',
          sortable: true
        },
        {
          name: 'nodes',
          required: true,
          label: 'Nodes',
          align: 'left',
          field: 'nodes',
          sortable: true
        },
        {
          name: 'percentage',
          required: true,
          label: '%',
          align: 'left',
          field: 'percentage',
          sortable: true
        }
      ]
    },
    ispsColumns: function () {
      return [
        {
          name: 'name',
          required: true,
          label: 'Provider (' + this.totalNodes + ' total nodes)',
          align: 'left',
          field: 'name',
          sortable: true
        },
        {
          name: 'nodes',
          required: true,
          label: 'Nodes',
          field: 'nodes',
          sortable: true
        },
        {
          name: 'percentage',
          required: true,
          label: '%',
          field: 'percentage',
          sortable: true
        }
      ]
    },
    totalNodes: function () {
      let totalNodes = 0
      for (let continent in this.nodes) {
        totalNodes += this.nodes[continent]['count']
      }
      return totalNodes
    },
    countriesTableData: function () {
      let countriesTableData = []
      for (let continent in this.nodes) {
        for (let country in this.nodes[continent]) {
          if (country !== 'count') {
            countriesTableData.push({
              name: country || 'Not detected',
              nodes: this.nodes[continent][country]['count'],
              percentage: parseFloat(this.nodes[continent][country]['count'] / this.totalNodes * 100).toFixed(2) + '%',
              color: randomColor()
            })
          }
        }
      }
      countriesTableData = countriesTableData.filter(val => val.nodes)
      countriesTableData.sort(function (a, b) { return (a.nodes > b.nodes) ? -1 : ((b.nodes > a.nodes) ? 1 : 0) })
      return countriesTableData
    },
    statesTableData: function () {
      let statesTableData = {}
      for (let continent in this.nodes) {
        for (let country in this.nodes[continent]) {
          statesTableData[country] = []
          for (let mostSpecificSubdivision in this.nodes[continent][country]) {
            if (mostSpecificSubdivision !== 'count') {
              statesTableData[country].push({
                name: mostSpecificSubdivision || 'Not detected',
                nodes: this.nodes[continent][country][mostSpecificSubdivision]['count'],
                percentage: parseFloat(this.nodes[continent][country][mostSpecificSubdivision]['count'] / this.nodes[continent][country]['count'] * 100).toFixed(2) + '%'
              })
            }
          }
          statesTableData[country].sort(function (a, b) { return (a.nodes > b.nodes) ? -1 : ((b.nodes > a.nodes) ? 1 : 0) })
        }
      }
      return statesTableData
    }
  },

  mounted () {
    this.map = window.L.map('nodesMap').setView([50.0, 0.0], 3)
    this.markers = window.L.markerClusterGroup()
    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 20,
      attribution: 'Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(this.map)
    this.loadData()
    this.interval = setInterval(() => {
      this.checkLastUpdated()
    }, 10000)
  },
  beforeDestroy: function () {
    clearInterval(this.interval)
  },

  methods: {
    onResize (size) {
      this.$store.commit({type: 'data/setVar', key: 'containerHeight', value: size.height})
      this.$store.commit({type: 'data/setVar', key: 'mapHeightChanged', value: false})
    },
    getStatesTableData: function (country) {
      return this.statesTableData[country]
    },
    onlineColor: function (lastOnline) {
      if (lastOnline === 'now') {
        return 'greenyellow'
      }
      return 'yellow'
    },
    onlineText: function (lastOnline) {
      if (lastOnline === 'now') {
        return 'Online'
      }
      return 'Last online ' + window.moment(lastOnline + '+00:00').fromNow()
    },
    onlineTextTable: function (lastOnline) {
      if (lastOnline !== 'now') {
        return window.moment(lastOnline + '+00:00').fromNow()
      }
      return 'Online'
    },
    onlineSince: function (firstOnline) {
      return window.moment(firstOnline + '+00:00').fromNow()
    },
    createHref: function (value, https) {
      let httpsPrefix = ''
      if (https === 'Yes') {
        httpsPrefix = 's'
      }
      let ipv6OpenBracket = value.indexOf(':') !== -1 ? '[' : ''
      let ipv6CloseBracket = value.indexOf(':') !== -1 ? ']' : ''
      return 'http' + httpsPrefix + '://' + ipv6OpenBracket + value + ipv6CloseBracket
    },
    getIspCountryTableData: function (country) {
      let ispCountryTableData = []
      let countryTotal = 0
      for (let continent in this.nodes) {
        for (let cntr in this.nodes[continent]) {
          if (country === cntr) {
            countryTotal = this.nodes[continent][country]['count']
            break
          }
        }
      }
      for (let isp in this.ispsCountry[country]) {
        ispCountryTableData.push({
          name: isp || 'Not detected',
          nodes: this.ispsCountry[country][isp],
          percentage: parseFloat(this.ispsCountry[country][isp] / countryTotal * 100).toFixed(2) + '%'
        })
      }
      ispCountryTableData.sort(function (a, b) { return (a.nodes > b.nodes) ? -1 : ((b.nodes > a.nodes) ? 1 : 0) })
      return ispCountryTableData
    },
    drawMap () {
      this.isps = {}
      this.ispsCountry = {}
      this.nodes = {}
      this.listTableData = []
      this.onlineNodes = 0
      this.selectedOnlineNodes = 0
      this.selectedTotalNodes = 0
      let markerList = []
      let skip = false
      let data = this.$store.state.data.vars.nodesData
      let now = window.moment()
      let website = 'No'
      this.markers.clearLayers()
      for (let continent in data) {
        this.nodes[continent] = { count: 0 }
        for (let country in data[continent]) {
          this.nodes[continent][country] = { count: 0 }
          this.ispsCountry[country] = {}
          for (let mostSpecificSubdivision in data[continent][country]) {
            this.nodes[continent][country][mostSpecificSubdivision] = { count: 0 }
            for (let city in data[continent][country][mostSpecificSubdivision]) {
              for (let node in data[continent][country][mostSpecificSubdivision][city]) {
                let nodeData = data[continent][country][mostSpecificSubdivision][city][node]
                if (nodeData['percentage'] >= this.$store.state.data.vars.minimumPercentage && now.diff(window.moment(nodeData['firstOnline'] + '+00:00'), 'days') >= this.$store.state.data.vars.minimumDays) {
                  if (nodeData['lastOnline'] === 'now') {
                    this.onlineNodes++
                  }
                  if (nodeData['wi'] === 'Yes' || nodeData['wh'] === 'Yes') {
                    website = 'Yes'
                  } else {
                    website = 'No'
                  }
                  this.listTableData.push({
                    rank: nodeData['rank'],
                    ip: nodeData['ip'],
                    host: nodeData['host'],
                    lastOnline: nodeData['lastOnline'],
                    firstOnline: nodeData['firstOnline'],
                    percentage: nodeData['percentage'],
                    isp: nodeData['isp'],
                    country: country,
                    state: mostSpecificSubdivision,
                    city: city,
                    website: website,
                    wi: nodeData['wi'],
                    wh: nodeData['wh'],
                    whs: nodeData['whs']
                  })
                  this.nodes[continent]['count']++
                  this.nodes[continent][country]['count']++
                  this.nodes[continent][country][mostSpecificSubdivision]['count']++
                  if (this.nodesFilter === 'isp') {
                    skip = true
                    for (let isp in this.selectedIsps) {
                      if (this.selectedIsps[isp]['name'] === nodeData['isp']) {
                        skip = false
                      }
                    }
                  }
                  if (((this.nodesFilter && !skip) || !this.nodesFilter)) {
                    if ((this.nodesFilter && !skip)) {
                      this.selectedTotalNodes++
                      if (nodeData['lastOnline'] === 'now') {
                        this.selectedOnlineNodes++
                      }
                    }
                    let ip = nodeData['ip']
                    let host = nodeData['host']
                    let ipv6OpenBracket = ip.indexOf(':') !== -1 ? '[' : ''
                    let ipv6CloseBracket = ip.indexOf(':') !== -1 ? ']' : ''
                    let hostIpv6OpenBracket = host.indexOf(':') !== -1 ? '[' : ''
                    let hostIpv6CloseBracket = host.indexOf(':') !== -1 ? ']' : ''
                    let hyperlinkIp = ip
                    if (nodeData['wi'] === 'Yes') {
                      hyperlinkIp = '<a href="http://' + ipv6OpenBracket + ip + ipv6CloseBracket + '" target="_blank">' + ip + '</a>'
                    }
                    let hyperlinkHost = host
                    let hyperlinkHostHttps = ''
                    if (nodeData['wh'] === 'Yes') {
                      if (nodeData['whs'] === 'Yes') {
                        hyperlinkHostHttps = 's'
                      }
                      hyperlinkHost = '<a href="http' + hyperlinkHostHttps + '://' + hostIpv6OpenBracket + host + hostIpv6CloseBracket + '" target="_blank">' + host + '</a>'
                    }
                    let popup =
                      '<div style="display: table;">' +
                        '<div style="display: table-row;">' +
                          '<div style="display: table-cell;"><i class="material-icons" style="color: ' + this.onlineColor(nodeData['lastOnline']) + '">fiber_manual_record</i></div>' +
                          '<div style="display: table-cell;">' + this.onlineText(nodeData['lastOnline']) + '</div>' +
                        '</div>' +
                        '<div style="display: table-row;"><div style="display: table-cell;">IP address:</div><div style="display: table-cell;">' + hyperlinkIp + '</div></div>' +
                        '<div style="display: table-row;"><div style="display: table-cell;">Hostname:</div><div style="display: table-cell;">' + hyperlinkHost + '</div></div>' +
                        '<div style="display: table-row;"><div style="display: table-cell;">City:</div><div style="display: table-cell;">' + city + '</div></div>' +
                        '<div style="display: table-row;"><div style="display: table-cell;">State/Province:</div><div style="display: table-cell;">' + mostSpecificSubdivision + '</div></div>' +
                        '<div style="display: table-row;"><div style="display: table-cell;">Country:</div><div style="display: table-cell;">' + country + '</div></div>' +
                        '<div style="display: table-row;">' +
                          '<div style="display: table-cell;">Provider:</div>' +
                          '<div style="display: table-cell;"><a href="https://www.google.com/search?q=' + nodeData['isp'] + '" target="_blank">' + nodeData['isp'] + '</a></div>' +
                        '</div>' +
                        '<div style="display: table-row;">' +
                          '<div style="display: table-cell;">Online:</div>' +
                          '<div style="display: table-cell;">' + nodeData['percentage'] + '% since ' + window.moment(nodeData['firstOnline'] + '+00:00').fromNow() + '</div>' +
                        '</div>' +
                      '</div>'
                    let marker = window.L.marker([nodeData['lat'], nodeData['long']])
                    marker.bindPopup(popup)
                    markerList.push(marker)
                  }

                  if (!this.ispsCountry[country][nodeData['isp']]) {
                    this.ispsCountry[country][nodeData['isp']] = 1
                  } else {
                    this.ispsCountry[country][nodeData['isp']]++
                  }
                  if (!this.isps[nodeData['isp']]) {
                    this.isps[nodeData['isp']] = 1
                  } else {
                    this.isps[nodeData['isp']]++
                  }
                }
              }
            }
          }
        }
      }
      this.markers.addLayers(markerList)
      this.map.addLayer(this.markers)

      this.ispsTableData = []
      for (let isp in this.isps) {
        this.ispsTableData.push({
          name: isp,
          nodes: this.isps[isp],
          percentage: parseFloat(this.isps[isp] / this.totalNodes * 100).toFixed(2) + '%',
          color: randomColor()
        })
      }
      this.ispsTableData.sort(function (a, b) { return (a.nodes > b.nodes) ? -1 : ((b.nodes > a.nodes) ? 1 : 0) })

      this.listTableData.sort(function (a, b) { return (a.rank < b.rank) ? -1 : ((b.rank < a.rank) ? 1 : 0) })

      // Update charts
      this.$nextTick(function () {
        this.$refs.countriesChart.updateChart()
        this.$refs.ispsChart.updateChart()
      })
    },
    loadData () {
      window.axios({
        url: '/data.json',
        method: 'get',
        baseURL: this.$store.state.data.host
      })
        .then((response) => {
          this.$store.commit({type: 'data/setVar', key: 'todayStatsData', value: response.data.stats})
          this.$store.commit({type: 'data/setVar', key: 'nodesData', value: response.data.data})
          this.$store.commit({type: 'data/setVar', key: 'lastUpdated', value: window.moment()})
          this.drawMap()
          return true
        })
        .catch(function (error) {
          return 'Error! ' + error
        })
    },
    showSelectedIsps () {
      this.ispsSelected = true
      this.$store.commit({type: 'data/setVar', key: 'ispsOpened', value: false})
      this.nodesFilter = 'isp'
      this.drawMap()
    },
    showAll () {
      this.nodesFilter = null
      this.ispsSelected = false
      this.ispsOpened = false
      this.countriesOpened = false
      this.settingsOpened = false
      this.selectionOpened = false
      this.drawMap()
    },
    checkLastUpdated () {
      if (window.moment().diff(this.$store.state.data.vars.lastUpdated) > 1200000) {
        this.loadData()
      }
    },
    showSelectedInfo () {
      if (this.ispsSelected) {
        this.selectionOpened = true
      }
    }
  }
}
</script>

<style>
</style>
