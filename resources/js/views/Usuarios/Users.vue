<template>
  <main class="content">
    <div class="container-fluid p-0">
      <button @click="crearUsuario()" class="btn btn-primary float-end mt-n1">
        <i class="align-middle me-2" data-feather="plus-circle"></i>
        Agregar Usuario
      </button>
      <h1 class="h3 mb-3">
        <strong>
          Usuarios
        </strong>
      </h1>
      <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <h5 class="card-title mb-0">
                    Lista de Usuarios
                  </h5>
                </div>
                <div class="col-sm-6">
                  <input v-model="busqueda" placeholder="Buscar en servidor..." class="form-control mb-3" />
                </div>
              </div>
            </div>

            <EasyDataTable :headers="headers" :items="Usuarios" :loading="cargando" :rows-per-page="5"
              table-class="table table-hover my-0">
              <!-- 🎯 Columna de acciones personalizada -->
              <template #item-action="{ id, name, email }">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-primary me-1" @click="editarUsuario(id)">
                    Editar
                  </button>
                  <button class="btn btn-sm btn-outline-danger" @click="eliminarUsuario(id, name, email)">
                    Estatus
                  </button>
                </div>
              </template>
            </EasyDataTable>

          </div>
        </div>
      </div>
    </div>

    <Modal size="lg" ref="modalUsuario" id="modal-usuario" :title="Usuario.id ? 'Editar Usuario' : 'Registrar Usuario'">
      <!-- Contenido dinámico: slot principal -->
      <template #default>
        <form>
          <div class="row">

            <div class="mb-3">
              <label class="form-label" for="name">Nombre</label>
              <input v-model="Usuario.name" type="text" class="form-control" id="name" placeholder="Nombre completo...">
              <div v-if="errores.name" class="form-text text-danger">
                {{ errores.name[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="inputEmail4">Email</label>
              <input v-model="Usuario.email" type="email" class="form-control" id="inputEmail4" placeholder="Email">
              <div v-if="errores.email" class="form-text text-danger">
                  {{ errores.email[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="estatus">Estatus</label>
              <select v-model="Usuario.estatus" id="estatus" class="form-control">
                <option value="habilitado" selected>Habilitado</option>
                <option value="deshabilitado">Deshabilitado</option>
              </select>
              <div v-if="errores.estatus" class="form-text text-danger">
                  {{ errores.estatus[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="password">Contraseña</label>
              <input v-model="Usuario.password" type="password" class="form-control" id="password"
                placeholder="Password">
              <div v-if="errores.password" class="form-text text-danger">
                  {{ errores.password[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="password_confirmation">confirmacion de contraseña</label>
              <input v-model="Usuario.password_confirmation" type="password" class="form-control"
                id="password_confirmation" placeholder="Password">
            </div>

          </div>
        </form>
      </template>

      <!-- Footer dinámico: slot con nombre -->
      <template #footer>
        <button v-if="Usuario.id" class="btn btn-success" @click="guardarCambios(Usuario.id)">
          <i class="align-middle me-2" data-feather="save"></i>
          Guardar Cambios
        </button>
        <button v-else class="btn btn-success" @click="agregarUsuario()">
          <i class="align-middle me-2" data-feather="save"></i>
          Agregar
        </button>
      </template>
    </Modal>

  </main>
</template>

<script>
import api from '../../services/api';
import Modal from '../../components/Modal.vue';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';


export default {
  name: 'Users',
  components: {
    EasyDataTable,
    Modal
  },
  data() {
    return {
      headers: [
        { text: 'Id', value: 'id' },
        { text: 'Nombre', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Acciones', value: 'action' },
      ],
      Usuarios: [],
      cargando: false,
      Usuario: {
        id: null,
        name: '',
        email: '',
        estatus: '',
        password: '',
        password_confirmation: '',
      },
      busqueda: '',
      errores: {},
    }
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    busqueda: {
      handler(val) {
        this.consultar(val);
      },
      immediate: true
    }
  },
  methods: {
    async consultar(filtro = '') {
      this.cargando = true;
      try {
        const res = await api.get('/gestion/User', {
          params: { search: filtro }
        });
        this.Usuarios = res.data;
      } catch (error) {
        console.error('Error al consultar:', error);
      } finally {
        this.cargando = false;
      }
    },
    crearUsuario() {
      this.errores = {}; // 🔄 Limpia los errores
      this.Usuario = {
        id: null,
        name: '',
        email: '',
        estatus: 'habilitado',
        password: '',
        password_confirmation: '',
      };
      this.$refs.modalUsuario.abrir();
    },

    async agregarUsuario() {
      try {
        await api.post('/gestion/User', this.Usuario);
        this.consultar();
        this.$refs.modalUsuario.cerrar();

        this.Usuario = {
          id: null,
          name: '',
          email: '',
          estatus: '',
          password: '',
          password_confirmation: '',
        };
        this.errores = {}; // 🔄 Limpia los errores
        this.$swal.fire('Éxito', '✅ Registro agregado correctamente', 'success');
      } catch (error) {

        if (error.response && error.response.status == 422) {
          this.errores = error.response.data.errors;
          console.log(this.errores);
        } else {
          console.error(error);
          this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
        }

      }
    },

    editarUsuario(id) {
      this.errores = {}; // 🔄 Limpia los errores
      const encontrado = this.Usuarios.find(p => p.id === id);
      if (encontrado) {
        this.Usuario = { ...encontrado };
        this.$refs.modalUsuario.abrir();
      }
    },

    async guardarCambios(id) {
      try {
        await api.put(`/Permission/${id}`, this.Usuario);

        this.consultar();
        this.$refs.modalUsuario.cerrar();

        this.Usuario = {
          id: null,
          name: '',
          email: '',
          estatus: '',
          password: '',
          password_confirmation: '',
        };
        this.$swal.fire('Éxito', '✅ Registro actualizado correctamente', 'success');
        this.errores = {}; // 🔄 Limpia los errores
      } catch (error) {

        if (error.response && error.response.status === 422) {
          this.errores = error.response.data.errors;
        } else {
          console.error(error);
          this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
        }

      }
    }
  },
  mounted() {
    this.consultar();
  }
}
</script>

<style></style>