<template>
  <div>
    <div class="container-fluid p-0">
      <div class="row">
        <div class="col-sm-3">
          <h1 class="h3 mb-3">
            Lita
            <strong>
            </strong>
          </h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row">
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_profesor_id" class="form-label">Profesor</label>
                  <v-select disabled input-id="encabezado_profesor_id" v-model="selectEncabezadoProfesor" label="text"
                    placeholder="Seleccione un profesor" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_materia_id" class="form-label">Materia</label>
                  <v-select disabled input-id="encabezado_materia_id" v-model="selectEncabezadomateria" label="text"
                    placeholder="Seleccione una materia" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_semestre_id" class="form-label">Semestre</label>
                  <v-select disabled input-id="encabezado_semestre_id" v-model="selectEncabezadosemestre" label="text"
                    placeholder="Seleccione un semestre" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_grupo_id" class="form-label">Grado/Grupo</label>
                  <v-select disabled input-id="encabezado_grupo_id" v-model="selectEncabezadogrupo" label="text"
                    placeholder="Seleccione un grupo" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_carrera_id" class="form-label">Carrera</label>
                  <v-select disabled input-id="encabezado_carrera_id" v-model="selectEncabezadocarrera" label="text"
                    placeholder="Seleccione una carrera" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_ano_id" class="form-label">Año</label>
                  <v-select disabled input-id="encabezado_ano_id" v-model="selectEncabezadoano" label="text"
                    placeholder="Seleccione un ano" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
              </div>

              <div class="text-success">
                <hr>
              </div>

              <div class="row">

                <div class="col-sm-4 text-end col-md-5">
                  <button @click="escalaEvaluativa" v-if="selectSelectedParcial" class="btn btn-secondary mt-2">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    Escala Evaluativa.
                  </button>
                </div>
                <div class="col-sm-4 col-md-3">
                  <v-select v-model="selectSelectedParcial" :options="selectOptionsParciales" label="text"
                    :filterable="false" :loading="selectLoading" placeholder="Seleccione un parcial"
                    @search="selectFetchOptionsParciales" @change="handleChangeParcial" :reduce="option => option"
                    class="form-control mb-3" no-options="Seleccione un alumno"
                    no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                </div>

                <div class="col-sm-4 col-md-4">
                  <v-select v-model="selectSelectedUser" :options="selectOptions" label="text" :filterable="false"
                    :loading="selectLoading" placeholder="Seleccione un alumno" @search="selectFetchOptions"
                    @change="handleChange" :reduce="option => option.id" class="form-control mb-3"
                    no-options="Seleccione un alumno" no-results="No se encontraron resultados"
                    :selectable="option => !option.disabled" />
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <h5 class="card-title">
                    Lista de usuarios.
                  </h5>
                </div>
                <div class="col-sm-6">
                  <input v-model="busqueda" placeholder="Buscar en servidor..." class="form-control" />
                </div>
              </div>
            </div>


            <!-- Reemplaza el EasyDataTable con AG Grid -->
            <div class="ag-theme-alpine" style="height: auto; width: 100%;">
              <AgGridVue class="ag-theme-alpine" :rowData="rowData" :columnDefs="columnDefs"
                :defaultColDef="defaultColDef" :domLayout="'autoHeight'" />
            </div>

            <div class="row">
              <div class="col-sm-12 text-end">
                <router-link to="/seguimiento" class="btn btn-danger m-2">
                  <i class="bi bi-arrow-return-left"></i>
                  Regresar
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal size="lg" ref="modalLista" id="modal-lista" :title="'Escala Evaluativa.'">
      <!-- Contenido dinámico: slot principal -->
      <template #default>
        <form>
          <div class="row">
            <div class="mb-3 col-sm-4">
              <label for="ano" class="form-label">Escala Evaluativa</label>
              <v-select v-model="selectSelectedEscala" :options="selectOptionsEscala" label="text" :filterable="false"
                :loading="selectLoading" placeholder="Seleccione un alumno" @search="selectFetchOptionsEscala"
                @change="handleChangeEscala" :reduce="option => option.id" no-options="Seleccione un escala evaluativa"
                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
            </div>
          </div>

          <div class="row">
            <EasyDataTable :headers="headersEscala" :items="EscalasEvaluativas" :loading="cargando" :rows-per-page="5"
              table-class="table table-hover my-0">
              <!-- 🎯 Columna de acciones personalizada -->
              <template #item-action="{ id, nombre }">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-danger" @click="eliminarAlumno(id, nombre)">
                    Eliminar
                  </button>
                </div>
              </template>
            </EasyDataTable>
          </div>

        </form>
      </template>

      <!-- Footer dinámico: slot con nombre -->
      <template #footer>
        <button v-if="Seguimiento.id" class="btn btn-success" @click="guardarCambios(Seguimiento.id)">
          <i class="align-middle me-2" data-feather="save"></i>
          Guardar Cambios
        </button>
        <button v-else class="btn btn-success" @click="agregar()">
          <i class="align-middle me-2" data-feather="save"></i>
          Agregar
        </button>
      </template>
    </Modal>

  </div>
</template>

<script>

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import api from '../../../services/api';

import { AgGridVue } from 'ag-grid-vue3';

import EasyDataTable from 'vue3-easy-data-table';

import debounce from 'lodash/debounce';

import Modal from '../../../components/Modal.vue';


export default {
  name: 'Lista',
  components: {
    EasyDataTable,
    vSelect,
    Modal,
    AgGridVue,
  },
  data() {
    return {
      escalas: [],
      filas: [],
      cargando: false,
      headers: [],
      headersEscala: [
        { text: 'Id', value: 'id' },
        { text: 'nombre', value: 'nombre' },
        { text: 'Abreviatura', value: 'abreviatura' }
      ],
      alumnos: [],
      EscalasEvaluativas: [],
      Permiso: {
        id: null,
        name: '',
      },
      busqueda: '',
      errores: {},

      selectSelected: null,
      selectOptions: [],
      selectLoading: false,
      selectSearchTimeout: null,

      selectEncabezadoProfesor: null,
      selectEncabezadomateria: null,
      selectEncabezadosemestre: null,
      selectEncabezadogrupo: null,
      selectEncabezadocarrera: null,
      selectEncabezadoano: null,
      selectSelectedUser: null,

      selectSelectedEscala: null,
      selectOptionsEscala: [],

      selectSelectedParcial: null,
      selectOptionsParciales: [],

      Seguimiento: {
        id: null,
      },


      gridApi: null,
      gridColumnApi: null,
      defaultColDef: {
        sortable: true,
        resizable: true,
        filter: true,
        flex: 1,
        minWidth: 100,
      },

      columnDefs: [],
      rowData: [],

    }
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    // busqueda: {
    //   handler: debounce(function (val) {
    //     this.cargarEvaluacion(val);
    //   }, 300),
    //   immediate: true
    // },
    // selectSelectedParcial(val) {
    //   if (val?.id) this.cargarEvaluacion();
    // }
  },
  methods: {

    sanitize(str) {
      return str.replace(/\W+/g, '_');
    },

    async consultar() {
      try {
        const res = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/Seguimiento`);
        const { profesor, materia, semestre, grupo, carrera, año } = res.data.Seguimiento;
        this.selectEncabezadoProfesor = profesor;
        this.selectEncabezadomateria = materia;
        this.selectEncabezadosemestre = semestre;
        this.selectEncabezadogrupo = grupo;
        this.selectEncabezadocarrera = carrera;
        this.selectEncabezadoano = año;
        if (res.data.Parcial) {
          this.selectSelectedParcial = res.data.Parcial;
          this.cargarDatos();
        }
      } catch (error) {
        if (error.response?.status === 404) {
          this.$swal.fire('Error', '❌ La lista no existe', 'error');
          this.$router.replace({ name: 'seguimiento' });
        } else {
          console.error(error);
        }
      }
    },

    async consultarEscala(filtro = '') {
      this.cargando = true;
      try {
        const res = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/EscalasAsignadas`, {
          params: {
            search: filtro,
            parcial_id: this.selectSelectedParcial?.id ?? null
          }
        });
        this.EscalasEvaluativas = res.data;
      } catch (error) {
        console.error('Error al consultar:', error);
      } finally {
        this.cargando = false;
      }
    },

    async agregar() {
      try {
        await api.post('/Permission', this.Permiso);
        this.cargarParciales();
        this.$refs.modalPermiso.cerrar();

        this.Permiso = {
          id: null,
          name: ''
        };
        this.errores = {}; // 🔄 Limpia los errores
        this.$swal.fire('Éxito', '✅ Registro agregado correctamente', 'success');
      } catch (error) {

        if (error.response && error.response.status == 422) {
          this.errores = error.response.data.errors;
        } else {
          console.error(error);
          this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
        }

      }
    },

    selectFetchOptions(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading = true
        try {
          const response = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/User`, {
            params: { search }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptions = [
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

    // selectFetchOptionsEscala
    selectFetchOptionsEscala(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading = true
        try {
          const response = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/Escalas`, {
            params: {
              search: search,
              parcial_id: this.selectSelectedParcial?.id ?? null
            }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptionsEscala = [
            ...response.data.map(item => ({
              id: item.id,
              text: item.nombre
            }))
          ];
        } catch (error) {
          console.error('Error al cargar opciones:', error)
          this.selectOptionsEscala = []
        } finally {
          this.selectLoading = false
        }
      }, 300)
    },

    selectFetchOptionsParciales(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading = true
        try {
          const response = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/Parciales`, {
            params: { search }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptionsParciales = [
            ...response.data.map(item => ({
              id: item.id,
              text: item.nombre
            }))
          ];
        } catch (error) {
          console.error('Error al cargar opciones:', error)
          this.selectOptionsParciales = []
        } finally {
          this.selectLoading = false
        }
      }, 300)
    },

    async handleChange(alumno) {
      // Usar directamente el valor bindeado en v-model
      const alumno_id = this.selectSelectedUser;
      if (!alumno_id) return;
      try {
        await api.post(`/Estudiuante/Lista/${this.$route.params.id}/asignarUsuario`, {
          alumno_id: alumno_id, // Usamos el id del alumno
          seguimiento_id: this.$route.params.id,
        });
        this.selectSelectedUser = null; // Limpia el select después de asignar el alumno
        this.cargarParciales();
      } catch (error) {
        console.error('Error al asignar alumno:', error);
      }
    },

    // handleChangeParcial
    async handleChangeParcial(alumno) {
      // Usar directamente el valor bindeado en v-model
      const parcial = this.selectSelectedParcial;
      if (!parcial) return;
      try {
        console.log('si');
        this.cargarDatos();
      } catch (error) {
        console.error('Error: ', error);
      }
    },

    // handleChangeEscala
    async handleChangeEscala(alumno) {
      // Usar directamente el valor bindeado en v-model
      const escala_evaluativa_id = this.selectSelectedEscala;
      if (!escala_evaluativa_id) return;
      try {
        await api.post(`/Estudiuante/Lista/${this.$route.params.id}/asignarEscala`, {
          escala_evaluativa_id: escala_evaluativa_id, // Usamos el id del alumno
          parcial_id: this.selectSelectedParcial?.id ?? null,
        });
        this.selectSelectedEscala = null; // Limpia el select después de asignar el alumno
        this.consultarEscala();
      } catch (error) {
        console.error('Error al asignar alumno:', error);
      }
    },

    async escalaEvaluativa() {
      this.errores = {}; // 🔄 Limpia los errores
      this.Permiso = {
        id: null,
        name: '',
      };
      this.consultarEscala();
      this.$refs.modalLista.abrir();
    },

    async cargarDatos() {
      try {
        const { data } = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/evaluacion`, {
          params: {
            parcial_id: this.selectSelectedParcial.id
          },
        });
        this.columnDefs = [
          { field: 'nombre', headerName: 'Alumno' },
          { field: 'faltas', headerName: 'Faltas', editable: true },
          { field: 'suma', headerName: 'Suma', editable: true },
          { field: 'calificacion', headerName: 'Calificación', editable: true },
        ];
        // Agrega dinámicamente columnas por cada escala evaluativa
        data.escalas.forEach((escala) => {
          const abrev = escala.abreviatura;
          this.columnDefs.push(
            {
              field: `cantidades.${abrev}`,
              headerName: `${abrev} Cant`,
              editable: true,
            },
            {
              field: `puntajes.${abrev}`,
              headerName: `${abrev} Pts`,
              editable: true,
            }
          );
        });

        this.rowData = data.alumnos;
      } catch (error) {
        console.error('Error al cargar evaluación:', error);
      }
    },

  },
  mounted() {
    this.consultar();
  }
}
</script>

<style>
/* .ag-theme-alpine {
  --ag-header-background-color: #f8f9fa;
  --ag-odd-row-background-color: #f9f9f9;
  margin-top: 20px;
}

.ag-cell {
  display: flex;
  align-items: center;
  justify-content: center;
} */
</style>
