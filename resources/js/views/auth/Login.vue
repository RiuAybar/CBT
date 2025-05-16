<template>
  <!-- <main class="d-flex w-100">
    <div class="container d-flex flex-column">
      <div class="row vh-100">
        <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100"> -->
          <div class="d-table-cell align-middle">

            <div class="text-center mt-4">
              <h1 class="h2">¡Bienvenido de nuevo!</h1>
              <p class="lead">
                Inicie sesión en su cuenta para continuar
              </p>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="m-sm-3">
                  <form @submit.prevent="login">
                    <div class="mb-3">
                      <label class="form-label">Correo electrónico</label>
                      <input v-model="email" class="form-control form-control-lg" type="email"
                        placeholder="Introduce tu correo electrónico" />
                      <p class="text-danger mt-2" v-if="error">{{ error }}</p>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Contraseña</label>
                      <input v-model="password" class="form-control form-control-lg" type="password"
                        placeholder="Ingrese su contraseña" />
                    </div>
                    <!-- <div>
                      <div class="form-check align-items-center">
                        <input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me"
                          name="remember-me" checked>
                        <label class="form-check-label text-small" for="customControlInline">Acuérdate de mí</label>
                      </div>
                    </div> -->
                    <div class="d-grid gap-2 mt-3">
                      <button class="btn btn-lg btn-primary">Iniciar sesión</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="text-center mb-3">
              <router-link to="/restablecer/contrasena">¿Has olvidado tu contraseña?</router-link>
						</div>
          </div>
        <!-- </div>
      </div>
    </div>
  </main> -->
</template>

<script>
import api, { setAuthToken } from '../../services/api';

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: ''
    };
  },
  methods: {
    async login() {
      this.error = '';
      try {
        const res = await api.post('/login', {
          email: this.email,
          password: this.password
        });

        const token = res.data.token;
        localStorage.setItem('token', token);
        setAuthToken(token);
        // 🔥 Cargas los datos del usuario
        await this.$store.dispatch('auth/fetchUser');
        this.$router.push({ name: 'home' }); // ✅ Redirección desde methods

        // ✅ Redirigir manualmente SOLO si estamos en login
        // const redirectPath = localStorage.getItem('redirectPath');
        // localStorage.removeItem('redirectPath');

        // if (redirectPath) {
        //   this.$router.replace(redirectPath);
        // } else {
        //   this.$router.replace({ name: 'home' });
        // }

      } catch (e) {
        this.error = e.response?.data?.error || 'Error al iniciar sesión';
      }
    }
  }
};
</script>

<style></style>