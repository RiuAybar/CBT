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
                  <v-select disabled input-id="encabezado_profesor_id" v-model="selectEncabezado.profesor" label="text"
                    placeholder="Seleccione un profesor" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_materia_id" class="form-label">Materia</label>
                  <v-select disabled input-id="encabezado_materia_id" v-model="selectEncabezado.materia" label="text"
                    placeholder="Seleccione una materia" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_semestre_id" class="form-label">Semestre</label>
                  <v-select disabled input-id="encabezado_semestre_id" v-model="selectEncabezado.semestre" label="text"
                    placeholder="Seleccione un semestre" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_grupo_id" class="form-label">Grado/Grupo</label>
                  <v-select disabled input-id="encabezado_grupo_id" v-model="selectEncabezado.grupo" label="text"
                    placeholder="Seleccione un grupo" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_carrera_id" class="form-label">Carrera</label>
                  <v-select disabled input-id="encabezado_carrera_id" v-model="selectEncabezado.carrera" label="text"
                    placeholder="Seleccione una carrera" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
                <div class="mb-3 col-sm-4">
                  <label for="encabezado_ano_id" class="form-label">Año</label>
                  <v-select disabled input-id="encabezado_ano_id" v-model="selectEncabezado.año" label="text"
                    placeholder="Seleccione un ano" no-options="Seleccione una opción"
                    no-results="No se encontraron resultados" />
                </div>
              </div>

              <div class="text-success">
                <hr>
              </div>

              <div class="row">

                <div class="col-sm-4 text-end col-md-5">
                  <button @click="escalaEvaluativa" v-if="selectSelected.Parcial" class="btn btn-secondary mt-2">
                    <i class="bi bi-clipboard2-data-fill"></i>
                    Escala Evaluativa.
                  </button>
                </div>
                <div class="col-sm-4 col-md-3">
                  <v-select v-model="selectSelected.Parcial" :options="selectOptions.Parciales" label="text"
                    :filterable="false" :loading="selectLoading.Parcial" placeholder="Seleccione un parcial"
                    @search="selectFetchOptionsParciales" @change="handleChangeParcial" :reduce="option => option"
                    class="form-control mb-3" :selectable="option => !option.disabled">
                    <!-- Mensaje cuando no hay opciones iniciales -->
                    <template #no-options>
                      Buscar parcial
                    </template>
                  </v-select>

                </div>

                <div class="col-sm-4 col-md-4">
                  <v-select v-model="selectSelected.User" :options="selectOptions.User" label="text" :filterable="false"
                    :loading="selectLoading.User" placeholder="Seleccione un alumno" @search="selectFetchOptionsUser"
                    @change="handleChangeUser" :reduce="option => option.id" class="form-control mb-3"
                    no-options="Seleccione un alumno" no-results="No se encontraron resultados"
                    :selectable="option => !option.disabled">
                    <!-- Mensaje cuando no hay opciones iniciales -->
                    <template #no-options>
                      Buscar alumno
                    </template>
                  </v-select>
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
                :defaultColDef="defaultColDef" :domLayout="'autoHeight'" :pagination="true"
                @cellValueChanged="onCellValueChanged" />
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
              <v-select v-model="selectSelected.Escala" :options="selectOptions.Escala" label="text" :filterable="false"
                :loading="selectLoading.Escala" placeholder="Seleccione una opción" @search="selectFetchOptionsEscala"
                @change="handleChangeEscala" :reduce="option => option.id" no-options="Seleccione un escala evaluativa"
                no-results="No se encontraron resultados" :selectable="option => !option.disabled">
                <!-- Mensaje cuando no hay opciones iniciales -->
                <template #no-options>
                  Buscar Escala Evaluativa
                </template>
              </v-select>
            </div>
          </div>
          <div class="row">
            <EasyDataTable :headers="headersEscala" :items="EscalasEvaluativas" :loading="cargando" :rows-per-page="5"
              table-class="table table-hover my-0">
              <!-- 🎯 Columna de acciones personalizada -->
              <template #item-action="{ id, nombre, abreviatura, materiaParcialEscalaId }">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-danger"
                    @click="eliminarEscalaEvaluativa(materiaParcialEscalaId, nombre)">
                    Eliminar
                  </button>
                </div>
              </template>
            </EasyDataTable>
          </div>
        </form>
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
      selectEncabezado: {},
      busqueda: '',
      selectSelected: {
        Parcial: null,
        User: null,
        Escala: null,
      },
      selectOptions: {
        Parciales: [],
        User: [],
        Escala: [],
      },
      selectLoading: {
        Parcial: false,
        User: false,
        Escala: false,
      },
      // Escalas variables
      selectSearchTimeout: null,
      EscalasEvaluativas: [],
      cargando: false,
      headersEscala: [
        { text: 'Id', value: 'id' },
        { text: 'nombre', value: 'nombre' },
        { text: 'Abreviatura', value: 'abreviatura' },
        { text: 'Acciones', value: 'action' }
      ],

      // Lista

      columnDefs: [],
      defaultColDef: {
        sortable: true,
        resizable: true,
        filter: true,
        flex: 1,
        minWidth: 100,
      },
      rowData: [],

      errores: {}, // 🔄 Errores
    }
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    busqueda: {
      handler: debounce(function (val) {
        this.cargarDatos(val);
      }, 300),
      immediate: true
    },
  },
  methods: {
    async consultar() {
      try {
        const res = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/Seguimiento`);
        this.selectEncabezado = res.data.Seguimiento;
        if (res.data.Parcial) {
          this.selectSelected.Parcial = res.data.Parcial;
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
    //Busqueda de parciales en la base de datos
    selectFetchOptionsParciales(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading.Parcial = true;
        try {
          const response = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/Parciales`, {
            params: { search }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptions.Parciales = [
            ...response.data.map(item => ({
              id: item.id,
              text: item.nombre
            }))
          ];
        } catch (error) {
          this.selectOptions.Parciales = []
          console.error('Error al cargar opciones:', error)
        } finally {
          this.selectLoading.Parcial = false;
        }
      }, 300)
    },
    // Acciones para realizar despues de seleccionar un parcial
    async handleChangeParcial(alumno) {
      // Usar directamente el valor bindeado en v-model
      const parcial = this.selectSelected.Parcial;
      if (!parcial) return;
      try {
        this.cargarDatos();
      } catch (error) {
        console.error('Error: ', error);
      }
    },
    // Busqueda de usuarios en la base de datos
    selectFetchOptionsUser(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading.User = true;
        try {
          const response = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/User`, {
            params: { search }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptions.User = [
            ...response.data.map(item => ({
              id: item.id,
              text: item.name
            }))
          ];
        } catch (error) {
          console.error('Error al cargar opciones:', error)
          this.selectOptions.User = []
        } finally {
          this.selectLoading.User = false;
        }
      }, 300)
    },
    // Acciones para realizar despues de seleccionar un usuario
    async handleChangeUser(alumno) {
      // Usar directamente el valor bindeado en v-model
      const alumno_id = this.selectSelected.User;
      if (!alumno_id) return;
      try {
        await api.post(`/Estudiuante/Lista/${this.$route.params.id}/asignarUsuario`, {
          alumno_id: alumno_id, // Usamos el id del alumno
          seguimiento_id: this.$route.params.id,
        });
        this.selectSelected.User = null; // Limpia el select después de asignar el alumno
        this.cargarDatos();
      } catch (error) {
        console.error('Error al asignar alumno:', error);
      }
    },
    // Busqueda de escalas en la base de datos
    selectFetchOptionsEscala(search) {
      clearTimeout(this.selectSearchTimeout)
      this.selectSearchTimeout = setTimeout(async () => {
        this.selectLoading.Escala = true
        try {
          const response = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/Escalas`, {
            params: {
              search: search,
              parcial_id: this.selectSelected.Parcial?.id ?? null
            }
          });
          // Agregar la opción deshabilitada al inicio del array
          this.selectOptions.Escala = [
            ...response.data.map(item => ({
              id: item.id,
              text: item.nombre
            }))
          ];
        } catch (error) {
          console.error('Error al cargar opciones:', error)
          this.selectOptions.Escala = []
        } finally {
          this.selectLoading.Escala = false
        }
      }, 300)
    },
    // Acciones para realizar despues de seleccionar una Escala
    async handleChangeEscala(alumno) {
      // Usar directamente el valor bindeado en v-model
      const escala_evaluativa_id = this.selectSelected.Escala;
      if (!escala_evaluativa_id) return;
      try {
        await api.post(`/Estudiuante/Lista/${this.$route.params.id}/asignarEscala`, {
          escala_evaluativa_id: escala_evaluativa_id, // Usamos el id del alumno
          parcial_id: this.selectSelected.Parcial?.id ?? null,
        });
        this.selectSelected.Escala = null; // Limpia el select después de asignar el alumno
        this.consultarEscala();
        this.cargarDatos();
      } catch (error) {
        console.error('Error al asignar alumno:', error);
      }
    },
    // Modal para ver las materias asignadas
    async escalaEvaluativa() {
      this.errores = {}; // 🔄 Limpia los errores
      this.consultarEscala();
      this.$refs.modalLista.abrir();
    },
    // Buscar Escalas en base de datos
    async consultarEscala(filtro = '') {
      this.cargando = true;
      try {
        const res = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/EscalasAsignadas`, {
          params: {
            search: filtro,
            parcial_id: this.selectSelected.Parcial?.id ?? null
          }
        });
        this.EscalasEvaluativas = res.data;
      } catch (error) {
        console.error('Error al consultar:', error);
      } finally {
        this.cargando = false;
      }
    },
    //Eliminar Escala Evaluativa
    eliminarEscalaEvaluativa(id, nombre) {
      this.$swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el registro ${nombre}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(async (result) => {
        if (result.isConfirmed) {
          // 🚨 Petición DELETE a API
          try {
            await api.delete(`/Estudiuante/Lista/${id}/EliminarEscala`);
            this.cargarDatos();
            this.errores = {}; // 🔄 Limpia los errores
            this.$swal.fire('Éxito', `✅ Registro ${nombre} eliminado correctamente`, 'success');
          } catch (error) {
            if (error.response && error.response.status === 422) {
              this.errores = error.response.data.errors;
            } else {
              console.error(error);
              this.$swal.fire('Error', '❌ No se pudo eliminar el registro', 'error');
            }
          }
        }
      });
    },
    // Cargar registros de evaluaciones
    async cargarDatos(busqueda = '') {
      try {
        const { data } = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/evaluacion`, {
          params: {
            parcial_id: this.selectSelected.Parcial?.id,
            busqueda: busqueda,
          },
        });
        this.columnDefs = [
          { field: 'nombre', headerName: 'Alumno' },
          { field: 'faltas', headerName: 'Faltas', editable: true },
        ];
        // Agrega dinámicamente columnas por cada escala evaluativa
        data.escalas.forEach((escala) => {
          const abrev = escala.abreviatura;
          this.columnDefs.push(
            {
              headerName: `${abrev} Cant`,
              editable: true,
              valueGetter: params => params.data.cantidades?.[abrev] ?? null,
              valueSetter: params => {
                if (!params.data.cantidades) params.data.cantidades = {};
                params.data.cantidades[abrev] = params.newValue;
                return true;
              },
            },
            {
              headerName: `${abrev} Pts`,
              editable: true,
              valueGetter: params => params.data.puntajes?.[abrev] ?? null,
              valueSetter: params => {
                if (!params.data.puntajes) params.data.puntajes = {};
                params.data.puntajes[abrev] = params.newValue;
                return true;
              },
            }
            // {
            //   field: `cantidades.${abrev}`,
            //   headerName: `${abrev} Cant`,
            //   editable: true,
            // },
            // {
            //   field: `puntajes.${abrev}`,
            //   headerName: `${abrev} Pts`,
            //   editable: true,
            // }
          );
        });

        this.columnDefs.push(
          { field: 'suma', headerName: 'Suma', editable: true },
          { field: 'calificacion', headerName: 'Calificación', editable: true },
        );

        this.rowData = data.alumnos;
      } catch (error) {
        console.error('Error al cargar evaluación:', error);
      }
    },
    // Actualizar notas
    async onCellValueChanged({ data, colDef, newValue, oldValue, column }) {
      const listaId = data.lista_id;

      // Extraer datos que necesitas
      const payload = [];

      const cantidades = data.cantidades || {};
      const puntajes = data.puntajes || {};


      for (const abreviatura in cantidades) {
        const cantidad = cantidades[abreviatura];
        const puntaje = puntajes[abreviatura] ?? null;

        payload.push({
          // lista_id: listaId,
          escala_abreviatura: abreviatura,
          cantidad_obtenida: cantidad,
          puntaje_obtenido: puntaje,
        });
      }
      let abreviatura = column.getId().split('_')[0];
      abreviatura = abreviatura == 'calificacion' ? 'calificacion_parcial' : abreviatura;
      // const columnasDef = ['faltas', 'suma', 'calificacion'];
      // const ColumnaDef = columnasDef.;
      // console.log(abreviatura, newValue);
      // console.log(payload, data, newValue, column, abreviatura);
      try {
        await api.post(`/Estudiuante/Lista/${this.$route.params.id}/NotasPorAspecto`, {
          parcial_id: this.selectSelected?.Parcial?.id,
          lista_id: listaId,
          items: payload,
          value: newValue,
          name: abreviatura
        });
        this.$swal.fire({
          title: '✅ Guardado',
          text: 'Los datos han sido actualizados',
          icon: 'success',
          timer: 500,
          showConfirmButton: false
        });
        // this.$swal.fire('✅ Guardado', 'Los datos han sido actualizados', 'success', { timer: 1500 });
      } catch (error) {
        console.error(error);
        this.$swal.fire('❌ Error', 'No se pudo guardar', 'error');
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
