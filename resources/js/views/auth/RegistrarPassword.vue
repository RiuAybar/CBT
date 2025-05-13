<template>
  <div class="d-table-cell align-middle">

    <div class="text-center mt-4">
      <h1 class="h2">
        Restablecer contraseña
      </h1>
      <p class="lead">
        Ingresa tu contraseña.
      </p>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="m-sm-3">
          <form @submit.prevent="restablecerContraseña">
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input v-model="password" name="password" class="form-control form-control-lg" type="password"
                placeholder="Ingrese su contraseña" />
              <div v-if="errores.password" class="form-text text-danger">
                {{ errores.password[0] }}
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">confirmacion de contraseña</label>
              <input v-model="password_confirmation" name="password_confirmation" class="form-control form-control-lg"
                type="password" placeholder="Ingrese su contraseña" />
            </div>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-lg btn-primary">Iniciar sesión</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import api, { setAuthToken } from '../../services/api';

export default {
  name: 'RegistrarPassword',
  props: {
    token: String,
    email: String
  },
  data() {
    return {
      password: '',
      password_confirmation: '',
      errores: {}
    };
  },
  methods: {
    async restablecerContraseña() {
      this.errores = {};
      try {
        const res = await api.post('/password/reset', {
          token: this.token,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });
        this.$router.push({ name: 'login' }); // ✅ Redirección desde methods
        this.$swal.fire('Éxito', '✅ Contraseña actualizada correctamente', 'success');
      } catch (error) {
        // Resetear los errores
        this.errores = {};
        // this.errores = error.response?.data?.error || 'Error al iniciar sesión';
        if (error.response && error.response.status == 422) {
          // console.log(error.response.data.error);
          if (error.response.data.errors && error.response.data.errors.password) {
            this.errores = error.response.data.errors;
          } else if(error.response.data.errors) {
            const otherErrors = error.response.data.errors;
            console.log(otherErrors);
            Object.keys(otherErrors).forEach(key => {
              if (key !== 'password') {
                this.$swal.fire('Error', otherErrors[key][0], 'error');
              }
            });
          }else if(error.response.data.error){
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