export const setVar = (state, payload) => {
  state.vars[payload.key] = payload.value
}
export const setTodayStatsData = (state, payload) => {
  for (let type in payload.value) {
    state.vars.todayStatsData[type] = payload.value[type]
  }
}
export const copyTodayStatsData = (state) => {
  for (let type in state.vars.todayStatsData) {
    state.vars.statsData[type][window.moment().format('YYYY-MM-DD')] = state.vars.todayStatsData[type]
  }
}
