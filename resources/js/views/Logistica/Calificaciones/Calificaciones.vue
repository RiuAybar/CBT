<template>
  <div>
    <div class="container-fluid p-0">
      <h1 class="h3 mb-3">
        <strong>
          Calificaciones
        </strong>
      </h1>
      <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <h5 class="card-title mb-0">
                    Mis calificaciones
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
              <template #item-action="{ id, name, email, estatus, telefono, domicilio, localidadColonia, rol }">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-primary me-1" @click="editarUsuario(id)"
                    v-if="hasPermission(permisoSegunRol(rol))">
                    Editar
                  </button>
                  <button class="btn btn-sm btn-outline-danger" @click="cambiarEstatus(id, name, email)"
                    v-if="hasPermission(permisoSegunRolUser(rol))">
                    {{ estatusCapitalizado(estatus) }}
                  </button>
                </div>
              </template>
            </EasyDataTable>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import api from '../../../services/api';
import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';
import debounce from 'lodash/debounce';


export default {
  name: 'Users',
  components: {
    EasyDataTable,
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
      permisosPorRol: {
        Estudiante: "puede editar estudiantes",
        Profesor: "puede editar profesores",
        Orientador: "puede editar orientadores",
        Admin: "puede editar administradores",
      },

      permisosPorRolUser: {
        Estudiante: "puede desabilitar estudiantes",
        Profesor: "puede desabilitar profesores",
        Orientador: "puede desabilitar orientadores",
        Admin: "puede desabilitar administradores",
      },
      errores: {},
    }
  },
  computed: {
    ...mapGetters(['selectedYear']),
    ...mapGetters('auth', ['hasPermission'])
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    busqueda: {
      handler: debounce(function (val) {
        this.consultar(val);
      }, 300),
      immediate: true
    }
  },
  methods: {
    async consultar(filtro = '') {
      this.cargando = true;
      try {
        const res = await api.get(`/Estudiuante/Lista/VistaCalificaciones`, {
          params: {
            search: filtro,
            ano: this.selectedYear
          }
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
        this.$swal.fire('Error', '❌ No se pudo actualizar el registro', 'error');
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
    permisoSegunRol(rol) {
      if (!rol) return ""; // evita errores
      const rolLimpio = rol.trim(); // elimina espacios/tabulaciones
      return this.permisosPorRol[rolLimpio] || "";
    },
    permisoSegunRolUser(rol) {
      if (!rol) return ""; // evita errores
      const rolLimpio = rol.trim(); // elimina espacios/tabulaciones
      return this.permisosPorRolUser[rolLimpio] || "";
    },
  },
  mounted() {
    this.consultar();

  }
}
</script>

<style></style>