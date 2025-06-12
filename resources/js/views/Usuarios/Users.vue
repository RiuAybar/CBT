<template>
  <div>
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
              <template #item-action="{ id, name, email, estatus }">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-primary me-1" @click="editarUsuario(id)">
                    Editar
                  </button>
                  <button class="btn btn-sm btn-outline-danger" @click="cambiarEstatus(id, name, email)">
                    {{ estatusCapitalizado(estatus) }}
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
              <label class="form-label" for="email">Email</label>
              <input v-model="Usuario.email" type="email" class="form-control" id="email" placeholder="Email">
              <div v-if="errores.email" class="form-text text-danger">
                {{ errores.email[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="telefono">Teléfono {{ Usuario.telefono }}</label>
              <input v-model="Usuario.telefono" type="text" class="form-control" id="telefono" placeholder="Telefono"
                @input="validarTelefono">
              <div v-if="errores.telefono" class="form-text text-danger">
                {{ errores.telefono[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="domicilio">Domicilio</label>
              <input v-model="Usuario.domicilio" type="text" class="form-control" id="domicilio"
                placeholder="Domicilio">
              <div v-if="errores.domicilio" class="form-text text-danger">
                {{ errores.domicilio[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="localidadColonia">Localidad o Colonia</label>
              <input v-model="Usuario.localidadColonia" type="text" class="form-control" id="Localidad o Colonia"
                placeholder="localidadColonia">
              <div v-if="errores.localidadColonia" class="form-text text-danger">
                {{ errores.localidadColonia[0] }}
              </div>
            </div>

            <div class="mb-3 col-md-6">
              <label class="form-label" for="rol">Rol</label>
              <v-select v-model="selectSelected" :options="selectOptions" label="text" :filterable="false"
                :loading="selectLoading" placeholder="Seleccione un permiso" @search="selectFetchOptions"
                :reduce="option => option" no-options="Seleccione una opción"
                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
              <div v-if="errores.rol" class="form-text text-danger">
                {{ errores.rol[0] }}
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

  </div>
</template>

<script>
import api from '../../services/api';
import Modal from '../../components/Modal.vue';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import debounce from 'lodash/debounce';


export default {
  name: 'Users',
  components: {
    EasyDataTable,
    Modal,
    vSelect,
  },
  data() {
    return {
      headers: [
        { text: 'Id', value: 'id' },
        { text: 'Nombre', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Rol', value: 'rol' },
        { text: 'Telefono', value: 'telefono' },
        { text: 'Domicilio', value: 'domicilio' },
        { text: 'Localidad o Colonia', value: 'localidadColonia' },
        { text: 'Acciones', value: 'action' },
      ],
      Usuarios: [],
      cargando: false,
      Usuario: {
        id: null,
        name: '',
        email: '',
        rol: '',
        RolId: '',
        telefono: '',
        domicilio: '',
        localidadColonia: '',
        estatus: '',
        password: '',
        password_confirmation: '',
      },
      busqueda: '',

      selectSelected: {
        id: null,
        text: null,
      },
      selectOptions: [
        {
          id: null,
          text: 'Seleccione una opción',
          disabled: true
        }
      ],
      selectLoading: false,
      selectSearchTimeout: null,

      errores: {},
    }
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    busqueda: {
      handler: debounce(function (val) {
        this.consultar(val);
      },300),
      immediate: true
    }
  },
  methods: {
    async consultar(filtro = '') {
      this.cargando = true;
      try {
        const res = await api.get('/gestion/user', {
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
        rol: '',
        RolId: '',
        telefono: '',
        domicilio: '',
        localidadColonia: '',
        estatus: 'habilitado',
        password: '',
        password_confirmation: '',
      };
      this.selectOptions = [
        {
          id: null,
          text: 'Seleccione una opción',
          disabled: true
        }
      ],
        this.selectSelected = {
          id: null,
          text: null,
        },
        this.$refs.modalUsuario.abrir();
    },

    async agregarUsuario() {
      let timerInterval;

      try {
        // Mostrar alerta de "Creando..."
        this.$swal.fire({
          title: "Creando usuario...",
          html: "Espere mientras se procesa la información<br><b></b> milisegundos restantes.",
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            this.$swal.showLoading();
            const timer = this.$swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
              timer.textContent = `${this.$swal.getTimerLeft()}`;
            }, 100);
          },
          willClose: () => {
            clearInterval(timerInterval);
          }
        });
        if (this.selectSelected.id) {
          this.Usuario.RolId = this.selectSelected.id
        }

        // Ejecutar solicitud fuera de la alerta
        const response = await api.post('/gestion/user', this.Usuario);

        // Cierra la alerta de carga manualmente
        this.$swal.close();

        if (response.status === 201) {
          this.consultar();
          this.$refs.modalUsuario.cerrar();
          this.Usuario = {
            id: null,
            name: '',
            email: '',
            rol: '',
            RolId: '',
            telefono: '',
            domicilio: '',
            localidadColonia: '',
            estatus: '',
            password: '',
            password_confirmation: '',
          };
          this.errores = {};

          // Mostrar alerta de éxito
          await this.$swal.fire({
            icon: 'success',
            title: '✅ Éxito',
            text: 'Registro agregado correctamente',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
          });
        }

      } catch (error) {
        this.$swal.close(); // Cerrar alerta "Creando..." si hay error

        if (error.response && error.response.status === 422) {
          this.errores = error.response.data.errors;
          console.log(this.errores);
        } else {
          console.error(error);
          this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
        }
      }
    },

    editarUsuario(id) {
      console.log(this.Usuarios);
      this.errores = {}; // 🔄 Limpia los errores
      const encontrado = this.Usuarios.find(p => p.id === id);
      if (encontrado) {
        this.Usuario = { ...encontrado };
        this.selectOptions = [
          {
            id: this.Usuario?.RolId ?? null,
            text: this.Usuario?.rol ?? null,
            disabled: false
          }
        ];
        this.selectSelected = {
          id: this.Usuario?.RolId ?? null,
          text: this.Usuario?.rol ?? null,
        };
        this.$refs.modalUsuario.abrir();
      }
    },

    async guardarCambios(id) {
      try {

        if (this.selectSelected.id) {
          this.Usuario.RolId = this.selectSelected.id
        }

        await api.put(`/gestion/user/${id}`, this.Usuario);

        this.consultar();
        this.$refs.modalUsuario.cerrar();

        this.Usuario = {
          id: null,
          name: '',
          email: '',
          rol: '',
          RolId: '',
          telefono: '',
          domicilio: '',
          localidadColonia: '',
          estatus: '',
          password: '',
          password_confirmation: '',
        };
        this.errores = {}; // 🔄 Limpia los errores
        this.$swal.fire('Éxito', '✅ Registro actualizado correctamente', 'success');
      } catch (error) {

        if (error.response && error.response.status === 422) {
          this.errores = error.response.data.errors;
        } else {
          console.error(error);
          this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
        }

      }
    },
    estatusCapitalizado(Estatus) {
      if (!Estatus) return '';
      return Estatus.charAt(0).toUpperCase() + Estatus.slice(1).toLowerCase();
    },
    async cambiarEstatus(id) {
      try {
        await api.put(`/gestion/user/${id}/Estatus`, {});
        this.consultar();
        this.$swal.fire('Éxito', '✅ Registro actualizado correctamente', 'success');
      } catch (error) {
        console.error(error);
        this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
      }
    },
    validarTelefono() {
      const telefono = this.Usuario.telefono;
      const erroresTelefono = [];

      const soloNumeros = /^[0-9]*$/;
      if (!soloNumeros.test(telefono.toString())) {
        this.Usuario.telefono = this.Usuario.telefono.replace(/\D/g, '');
        erroresTelefono.push('El teléfono solo debe contener números.');
        // Elimina todo lo que no sea número
      }
      if (telefono.toString().length > 10) {
        this.Usuario.telefono = this.Usuario.telefono.slice(0, 10);
        erroresTelefono.push('El teléfono no debe exceder los 10 dígitos.');
      }

      this.errores.telefono = erroresTelefono.length ? erroresTelefono : [];
    },
    selectFetchOptions(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading = true;
        try {
          const response = await api.get(`/gestion/roles`, {
            params: { search }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptions = [
            {
              id: null,
              text: 'Seleccione una opción',
              disabled: true
            },
            ...response.data.map(item => ({
              id: item.id,
              text: item.name
            }))
          ];
        } catch (error) {
          console.error('Error al cargar opciones:', error)
          this.selectOptions = []
        } finally {
          this.selectLoading = false
        }
      }, 300)
    },

  },
  mounted() {
    this.consultar();
  }
}
</script>

<style></style>