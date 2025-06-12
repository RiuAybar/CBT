import { createStore } from 'vuex';
import auth from './modules/auth.js';

const store = createStore({
  state() {
    return {
      contador: 0,
      isCollapsed: false,
      selectedYear: localStorage.getItem('selectedYear') || new Date().getFullYear()
    };
  },
  mutations: {
    incrementar(state) {
      state.contador++;
    },
    reiniciar(state) {
      state.contador = 0;
    },
    SET_COLLAPSED(state, value) {
      state.isCollapsed = value;
    },
    TOGGLE_COLLAPSED(state) {
      state.isCollapsed = !state.isCollapsed;
    },
    SET_SELECTED_YEAR(state, year) {
      state.selectedYear = year;
      localStorage.setItem('selectedYear', year); // persistir en localStorage
    }
  },
  actions: {
    incrementarAsync({ commit }) {
      setTimeout(() => {
        commit('incrementar');
      }, 1000);
    },
    setCollapsed({ commit }, value) {
      commit('SET_COLLAPSED', value);
    },
    toggleCollapsed({ commit }) {
      commit('TOGGLE_COLLAPSED');
    },
    setSelectedYear({ commit }, year) {
      commit('SET_SELECTED_YEAR', year);
    }
  },
  getters: {
    dobleContador: (state) => state.contador * 2,
    isCollapsed: state => state.isCollapsed,
    selectedYear: (state) => state.selectedYear
  },
  modules: {
    auth
  }
});

export default store;
