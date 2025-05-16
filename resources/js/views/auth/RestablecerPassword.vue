<template>
  <div class="d-table-cell align-middle">

    <div class="text-center mt-3">
      <h1 class="h2">
        Restablecer contraseña
      </h1>
      <p class="lead">
        Ingresa tu correo electrónico para restablecer tu contraseña.
      </p>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="m-sm-3">
          <form @submit.prevent="restablecerContraseña">
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input v-model="email" name="email" class="form-control form-control-lg" type="email"
                placeholder="Introduce tu correo electrónico" />
              <div v-if="errores.email" class="form-text text-danger">
                {{ errores.email[0] }}
              </div>
            </div>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-lg btn-primary">Restablecer contraseña</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="text-center mb-3">
      ¿No tienes una cuenta? <router-link to="/login">Regístrate</router-link>
    </div>
  </div>
</template>

<script>
import api, { setAuthToken } from '../../services/api';

export default {
  name: 'RestablecerPassword',
  data() {
    return {
      email: '',
      errores: {}
    };
  },
  methods: {
    async restablecerContraseña() {
      this.errores = {};
      try {
        const res = await api.post('/sendResetLink', {
          email: this.email,
        });
        this.$router.push({ name: 'login' }); // ✅ Redirección desde methods
        this.$swal.fire('Éxito', '✅ Se te ha enviado un correo de recuperacion para restablecer tu contraseña.', 'success');
      } catch (error) {
        // Resetear los errores
        this.errores = {};
        // this.errores = error.response?.data?.error || 'Error al iniciar sesión';
        if (error.response && error.response.status == 422) {
          // console.log(error.response.data.error);
          if (error.response.data.errors && error.response.data.errors.email) {
            this.errores = error.response.data.errors;
          } else if (error.response.data.errors) {
            const otherErrors = error.response.data.errors;
            console.log(otherErrors);
            Object.keys(otherErrors).forEach(key => {
              if (key !== 'email') {
                this.$swal.fire('Error', otherErrors[key][0], 'error');
              }
            });
          } else if (error.response.data.error) {
            this.$swal.fire('Error', error.response.data.error, 'error');
          }
        } else {
          console.error(error);
          this.$swal.fire('Error', '❌ No se actualizar correctamente la contraseña', 'error');
        }
      }
    }
  }
};
</script>

<style></style>