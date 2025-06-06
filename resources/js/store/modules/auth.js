import api from '../../services/api';

const state = {
  user: null
};

const mutations = {
  SET_USER(state, user) {
    state.user = user;
  },
  CLEAR_USER(state) {
    state.user = null;
    localStorage.removeItem('token'); // Puedes mover esto al action si lo prefieres
  }
};

const actions = {
  setUser({ commit }, user) {
    commit('SET_USER', user);
  },
  clearUser({ commit }) {
    commit('CLEAR_USER');
  },
  async fetchUser({ commit }) {
    try {
      const res = await api.get('/user'); // Este endpoint debe incluir el rol y permisos
      commit('SET_USER', {
          ...res.data.user,
          avatar_url: res.data.avatar_url,
          role: res.data.role?.name || null,
          permissions: res.data.role?.permissions?.map(p => p) || []
        });
      console.log('✅ Usuario cargado:', res.data);
    } catch (error) {
      console.error('❌ Error al obtener el usuario:', error);
    }
  }
};

const getters = {
  isAuthenticated: state => !!state.user,
  getUser: state => state.user,
  getUserRole: state => state.user?.role || null,
  hasPermission: state => perm => {
    return state.user?.permissions?.includes(perm);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};
