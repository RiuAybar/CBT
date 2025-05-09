<template>
  <main class="d-flex w-100">
    <div class="container d-flex flex-column">
      <div class="row vh-100">
        <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
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
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- <div class="container mt-5" style="max-width: 400px">
    <h3>Iniciar Sesión</h3>
    <form @submit.prevent="login">
      <input v-model="email" class="form-control mb-2" placeholder="Correo" />
      <input v-model="password" class="form-control mb-2" type="password" placeholder="Contraseña" />
      <button class="btn btn-primary w-100">Entrar</button>
      <p class="text-danger mt-2" v-if="error">{{ error }}</p>
    </form>
  </div> -->

  <!-- <form @submit.prevent="login">
      <input v-model="email" placeholder="Correo" class="form-control" />
      <input type="password" v-model="password" placeholder="Contraseña" class="form-control mt-2" />
      <button class="btn btn-primary mt-2">Entrar</button>
      <p v-if="error" class="text-danger">{{ error }}</p>
    </form> -->
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
      } catch (e) {
        this.error = e.response?.data?.error || 'Error al iniciar sesión';
      }
    }
  }
};
</script>

<style>
</style>