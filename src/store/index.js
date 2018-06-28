import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

import data from './module-data'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    data: data
  },
  plugins: [createPersistedState()]
})

export default store
