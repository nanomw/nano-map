<template>
  <q-layout view="lHh Lpr lff">
    <q-layout-header reveal>
      <q-resize-observable @resize="onResize" />
      <q-toolbar color="primary" glossy>
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
        >
          <q-icon name="menu" />
        </q-btn>

        <q-toolbar-title>
          Nano Map
          <div slot="subtitle">Map of 24/7 nodes</div>
        </q-toolbar-title>

        <span v-show="$route.path === '/' || $route.meta.error404">
          <q-btn
            flat
            class="within-iframe-hide"
            icon="fas fa-globe"
            @click="openCountries"></q-btn>
          <q-btn
            flat
            class="within-iframe-hide"
            icon="fas fa-chart-bar"
            @click="openIsps"></q-btn>
          <q-btn
            flat
            class="within-iframe-hide"
            icon="list"
            @click="openList"></q-btn>
          <q-btn
            flat
            class="within-iframe-hide"
            icon="bar_chart"
            @click="$router.push('/stats')"></q-btn>
        </span>
        <q-btn
          flat
          class="within-iframe-hide"
          icon="arrow_back"
          @click="$router.push('/')"
          v-show="$route.path === '/stats'"></q-btn>

      </q-toolbar>

    </q-layout-header>

    <q-layout-drawer
      v-model="leftDrawerOpen"
      content-class="bg-grey-2"
    >
      <q-list
        no-border
        link
        inset-delimiter
      >
        <q-list-header>Essential Links</q-list-header>
        <q-item @click.native="openURL('https://github.com/nanomw/nano-map')">
          <q-item-side icon="fab fa-github" />
          <q-item-main label="Nano Map GitHub" sublabel="github.com/nanomw/nano-map" />
        </q-item>
        <q-item @click.native="openURL('https://nano.org')">
          <q-item-side icon="timeline" />
          <q-item-main label="Nano website" sublabel="nano.org" />
        </q-item>
        <q-item @click.native="openURL('https://github.com/nanocurrency/raiblocks')">
          <q-item-side icon="fab fa-github" />
          <q-item-main label="Nano GitHub" sublabel="github.com/nanocurrency/raiblocks" />
        </q-item>
        <q-item @click.native="openURL('https://reddit.com/r/nanocurrency')">
          <q-item-side icon="fab fa-reddit" />
          <q-item-main label="Reddit" sublabel="reddit.com/r/nanocurrency" />
        </q-item>
        <q-item @click.native="openURL('https://chat.nano.org/')">
          <q-item-side icon="fab fa-discord" />
          <q-item-main label="Discord chat" sublabel="chat.nano.org" />
        </q-item>
        <q-item @click.native="openURL('https://twitter.com/nanocurrency')">
          <q-item-side icon="fab fa-twitter" />
          <q-item-main label="Twitter" sublabel="@nanocurrency" />
        </q-item>
      </q-list>
    </q-layout-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { openURL } from 'quasar'

export default {
  name: 'LayoutDefault',
  data () {
    return {
      leftDrawerOpen: false
    }
  },
  methods: {
    onResize (size) {
      if (!this.$store.state.data.vars.mapHeightChanged) {
        this.$store.commit({type: 'data/setVar', key: 'headerHeight', value: size.height})
        this.$store.commit({type: 'data/setVar', key: 'containerHeight', value: this.$store.state.data.vars.containerHeight - size.height})
        this.$store.commit({type: 'data/setVar', key: 'mapHeightChanged', value: true})
      }
    },
    openURL,
    openIsps: function () {
      this.$store.commit({type: 'data/setVar', key: 'ispsOpened', value: true})
    },
    openList: function () {
      this.$store.commit({type: 'data/setVar', key: 'listOpened', value: true})
    },
    openCountries: function () {
      this.$store.commit({type: 'data/setVar', key: 'countriesOpened', value: true})
    }
  }
}
</script>

<style>
</style>
