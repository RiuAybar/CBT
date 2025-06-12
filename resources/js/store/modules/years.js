// store/modules/years.js
export default {
  namespaced: true,
  state: () => ({
    selectedYear: new Date().getFullYear()
  }),
  mutations: {
    SET_SELECTED_YEAR(state, year) {
      state.selectedYear = year;
    }
  },
  actions: {
    setSelectedYear({ commit }, year) {
      commit('SET_SELECTED_YEAR', year);
    }
  },
  getters: {
    selectedYear: (state) => state.selectedYear
  }
}
