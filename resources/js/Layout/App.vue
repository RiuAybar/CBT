<template>
  <router-view />
</template>

<script>
import { mapGetters } from 'vuex';
import api, { setAuthToken } from '../services/api';

export default {
  name: 'App',
  comptued: {
    ...mapGetters('auth', ['getUser'])
  },
  methods: {
    async validarToken() {
      const token = localStorage.getItem('token');

      if (token) {
        setAuthToken(token);

        try {
          const res = await api.get('/user');
          const payload = {
            ...res.data.user,
            avatar_url: res.data.avatar_url,
            role: res.data.role?.name || null,
            permissions: res.data.role?.permissions || []
          };
          this.$store.dispatch('auth/setUser', payload);
          // console.log('Usuario autenticado:', res.data);
        } catch (error) {
          console.warn('Token inválido. Redirigiendo al login.');
          this.$store.dispatch('auth/clearUser');
          this.$router.push({ name: 'login' });
        }
      } else {
        //   // this.$router.push({ name: 'login' });
      }
    }
  },
  mounted() {
    this.validarToken();
  }
};
</script>