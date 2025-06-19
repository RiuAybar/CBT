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
                    @search="selectFetchOptionsParciales" :reduce="option => option" class="form-control mb-3"
                    no-options="Seleccione un alumno" no-results="No se encontraron resultados"
                    :selectable="option => !option.disabled" />
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

            <!-- Tabla editable -->
            <EasyDataTable :headers="headers" :items="computedFilas" :loading="cargando" :rows-per-page="5"
              table-class="table table-hover my-0">

              <!-- Campos editables: faltas, suma, calificación -->
              <template #item-faltas="{ rowIndex }">
                <input v-if="filas[rowIndex]" v-model="filas[rowIndex].faltas" type="number"
                  class="form-control form-control-sm" />
              </template>

              <template #item-suma="{ rowIndex }">
                <input v-if="filas[rowIndex]" v-model="filas[rowIndex].suma" type="number"
                  class="form-control form-control-sm" />
              </template>

              <template #item-calificacion="{ rowIndex }">
                <input v-if="filas[rowIndex]" v-model="filas[rowIndex].calificacion" type="number"
                  class="form-control form-control-sm" />
              </template>

              <!-- Campos dinámicos para cada escala -->
              <template v-for="escala in escalas" :key="'cant_' + escala.abreviatura"
                v-slot:[`item-cant_${escala.abreviatura}`]="{ rowIndex }">
                <span>TEST CANT {{ escala.abreviatura }}</span>
              </template>

              <template v-for="escala in escalas" :key="'pts_' + escala.abreviatura"
                v-slot:[`item-pts_${escala.abreviatura}`]="{ rowIndex }">
                <span>TEST PTS {{ escala.abreviatura }}</span>
              </template>

              <!-- Botón de guardar por fila -->
              <template #item-action="{ rowIndex }">
                <button class="btn btn-sm btn-success" @click="guardarEvaluacion(rowIndex)">
                  Guardar
                </button>
              </template>
            </EasyDataTable>


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
              <!-- <div v-if="errores.ano" class="form-text text-danger">
                {{ errores.ano[0] }}
              </div> -->
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
import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

import debounce from 'lodash/debounce';

import Modal from '../../../components/Modal.vue';


export default {
  name: 'Lista',
  components: {
    EasyDataTable,
    vSelect,
    Modal,
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
      }
    }
  },
  computed: {
    computedFilas() {
      return this.filas.map((f, idx) => ({ ...f }));
    }
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    busqueda: {
      handler: debounce(function (val) {
        this.cargarEvaluacion(val);
      }, 300),
      immediate: true
    },
    selectSelectedParcial(val) {
      if (val?.id) this.cargarEvaluacion();
    }
  },
  methods: {
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
          this.selectFetchOptionsParciales();
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

    async cargarEvaluacion() {
      if (!this.selectSelectedParcial?.id) return;
      this.cargando = true;
      try {
        const res = await api.get(`/Estudiuante/Lista/${this.$route.params.id}/evaluacion`, {
          params: { parcial_id: this.selectSelectedParcial.id }
        });
        this.escalas = res.data.escalas;
        const alumnos = res.data.alumnos;

        this.headers = [
          { text: 'Nombre', value: 'nombre' },
          { text: 'Faltas', value: 'faltas' },
          ...this.escalas.map(e => ({ text: `${e.abreviatura} (Cant)`, value: `cant_${e.abreviatura}` })),
          ...this.escalas.map(e => ({ text: `${e.abreviatura} (Pts)`, value: `pts_${e.abreviatura}` })),
          { text: 'Suma', value: 'suma' },
          { text: 'Calificación', value: 'calificacion' },
          { text: 'Acciones', value: 'action' }
        ];

        this.filas = alumnos.map(alumno => {
          const fila = {
            lista_id: alumno.lista_id,
            nombre: alumno.nombre,
            faltas: alumno.faltas ?? 0,
            suma: alumno.suma ?? 0,
            calificacion: alumno.calificacion ?? 0
          };

          this.escalas.forEach(e => {
            fila[`cant_${e.abreviatura}`] = alumno.cantidades?.[e.abreviatura] ?? '';
            fila[`pts_${e.abreviatura}`] = alumno.puntajes?.[e.abreviatura] ?? '';
          });

          return fila;
        });
      } catch (e) {
        console.error('Error cargando evaluación:', e);
      } finally {
        this.cargando = false;
      }
    },

    async guardarEvaluacion(index) {
      const fila = this.filas[index];
      const cantidades = {};
      const puntajes = {};

      this.escalas.forEach(e => {
        cantidades[e.abreviatura] = fila[`cant_${e.abreviatura}`];
        puntajes[e.abreviatura] = fila[`pts_${e.abreviatura}`];
      });

      try {
        await api.post(`/Estudiuante/Lista/${this.$route.params.id}/evaluacion`, {
          parcial_id: this.selectSelectedParcial.id,
          alumnos: [
            {
              lista_id: fila.lista_id,
              faltas: fila.faltas,
              suma: fila.suma,
              calificacion: fila.calificacion,
              cantidades,
              puntajes
            }
          ]
        });
        this.$swal.fire('Guardado', 'Evaluación registrada con éxito', 'success');
      } catch (error) {
        console.error(error);
        this.$swal.fire('Error', 'No se pudo guardar la evaluación', 'error');
      }
    },

    // async cargarEvaluacion() {
    //   // console.log(!this.selectSelectedParcial?.id);
    //   if (!this.selectSelectedParcial?.id) return
    //   this.cargando = true
    //   try {
    //     const res = await axios.get(`/api/Estudiuante/Lista/${this.$route.params.id}/evaluacion`, {
    //       params: { parcial_id: this.selectSelectedParcial.id }
    //     })
    //     this.escalas = res.data.escalas
    //     this.alumnos = res.data.alumnos
    //     this.construirTabla()
    //   } catch (err) {
    //     console.error('Error cargando evaluación:', err)
    //   } finally {
    //     this.cargando = false
    //   }
    // },
    // construirTabla() {
    //   // Construir headers dinámicos
    //   this.headers = [
    //     { text: 'Nombre', value: 'nombre' },
    //     { text: 'Faltas', value: 'faltas' },
    //     ...this.escalas.map(e => ({ text: `${e.abreviatura} (Cant)`, value: `cant_${e.abreviatura}` })),
    //     ...this.escalas.map(e => ({ text: `${e.abreviatura} (Pts)`, value: `pts_${e.abreviatura}` })),
    //     { text: 'Suma', value: 'suma' },
    //     { text: 'Calificación', value: 'calificacion' }
    //   ]

    //   // Construir filas con datos
    //   this.filas = this.alumnos.map(alumno => {
    //     const fila = {
    //       nombre: alumno.nombre,
    //       faltas: alumno.faltas,
    //       suma: alumno.suma,
    //       calificacion: alumno.calificacion
    //     }

    //     this.escalas.forEach(e => {
    //       fila[`cant_${e.abreviatura}`] = alumno.cantidades?.[e.abreviatura] ?? ''
    //       fila[`pts_${e.abreviatura}`] = alumno.puntajes?.[e.abreviatura] ?? ''
    //     })

    //     return fila
    //   })
    // },

    // async guardarEvaluacion(index) {
    //   const fila = this.filas[index];
    //   try {
    //     await api.post(`/Estudiuante/Lista/${this.$route.params.id}/evaluacion`, {
    //       parcial_id: this.selectSelectedParcial.id,
    //       alumnos: [
    //         {
    //           lista_id: fila.lista_id, // Asegúrate de incluir este campo en `alumnos` desde el backend
    //           faltas: fila.faltas ?? 0,
    //           suma: fila.suma ?? 0,
    //           calificacion: fila.calificacion ?? 0,
    //           cantidades: fila.cantidades,
    //           puntajes: fila.puntajes
    //         }
    //       ]
    //     });
    //     this.$swal.fire('✅ Guardado', 'Evaluación registrada', 'success');
    //   } catch (error) {
    //     console.error(error);
    //     this.$swal.fire('❌ Error', 'No se pudo guardar', 'error');
    //   }
    // },

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

    crear() {
      this.errores = {}; // 🔄 Limpia los errores
      this.Permiso = {
        id: null,
        name: '',
      };
      this.$refs.modalPermiso.abrir();
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
    }

  },
  mounted() {
    this.consultar();
    // this.cargarEvaluacion();
  }
}
</script>
