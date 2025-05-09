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
    }
};

const actions = {
    setUser({ commit }, user) {
        commit('SET_USER', user);
    },
    clearUser({ commit }) {
        commit('CLEAR_USER');
        localStorage.removeItem('token');
    },
    async fetchUser({ commit }) {
        try {
            const res = await api.get('/user'); // este endpoint debe devolver los datos del usuario autenticado
            commit('SET_USER', res.data);
        } catch (error) {
            console.error('Error al obtener el usuario:', error);
        }
    }
};

const getters = {
    isAuthenticated: state => !!state.user,
    getUser: state => state.user
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
};
