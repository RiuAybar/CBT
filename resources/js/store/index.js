import { createStore } from 'vuex';
import auth from './modules/auth.js';

const store = createStore({
  state() {
    return {
      contador: 0,
      isCollapsed: false
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
    }
  },
  getters: {
    dobleContador: (state) => state.contador * 2,
    isCollapsed: state => state.isCollapsed
  },
  modules: {
    auth
  }
});

export default store;
