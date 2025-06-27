<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Seguimiento
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Seguimiento
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de seguimiento
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input name="busqueda" v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Seguimientos" :loading="cargando" :rows-per-page="5"
                            table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template #item-action="{ id, nombre }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary me-1" @click="editar(id)">
                                        Editar
                                    </button>
                                    <router-link :to="`lista/${id}/edit`" class="btn btn-sm btn-outline-secondary me-1">
                                        ver lista
                                    </router-link>
                                    <button class="btn btn-sm btn-outline-info me-1" @click="formato1(id)">
                                        F1
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>

                    </div>
                </div>
            </div>
        </div>

        <Modal size="lg" ref="modalSeguimiento" id="modal-seguimiento"
            :title="Seguimiento.id ? 'Editar Seguimiento' : 'Agregar Seguimiento'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="row">
                        <div class="mb-3 col-sm-4">
                            <label for="profesor_id" class="form-label">Profesor</label>
                            <v-select input-id="profesor_id" v-model="selectProfesor" :options="selectOptionsProfesor"
                                label="text" :filterable="false" :loading="selectLoading"
                                placeholder="Seleccione un profesor" @search="selectProfesorOptions"
                                :reduce="option => option" no-options="Seleccione una opción"
                                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                            <div v-if="errores.profesor_id" class="form-text text-danger">
                                {{ errores.profesor_id[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="materia_id" class="form-label">Materia</label>
                            <v-select input-id="materia_id" v-model="selectMateria" :options="selectOptionsMateria"
                                label="text" :filterable="false" :loading="selectLoadingMateria"
                                placeholder="Seleccione un profesor" @search="selectMateriaOptions"
                                :reduce="option => option" no-options="Seleccione una opción"
                                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                            <div v-if="errores.materia_id" class="form-text text-danger">
                                {{ errores.materia_id[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="semestre_id" class="form-label">Semestre</label>
                            <v-select input-id="semestre_id" v-model="selectSemestre" :options="selectOptionsSemestre"
                                label="text" :filterable="false" :loading="selectLoadingSemestre"
                                placeholder="Seleccione un profesor" @search="selectSemestreOptions"
                                :reduce="option => option" no-options="Seleccione una opción"
                                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                            <div v-if="errores.semestre_id" class="form-text text-danger">
                                {{ errores.semestre_id[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="grupo_id" class="form-label">Grado/Grupo</label>
                            <v-select input-id="grupo_id" v-model="selectGrupo" :options="selectOptionsGrupo"
                                label="text" :filterable="false" :loading="selectLoadingGrupo"
                                placeholder="Seleccione un profesor" @search="selectGrupoOptions"
                                :reduce="option => option" no-options="Seleccione una opción"
                                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                            <div v-if="errores.grupo_id" class="form-text text-danger">
                                {{ errores.grupo_id[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="carrera_id" class="form-label">Carrera</label>
                            <v-select input-id="carrera_id" v-model="selectCarrera" :options="selectOptionsCarrera"
                                label="text" :filterable="false" :loading="selectLoadingCarrera"
                                placeholder="Seleccione un profesor" @search="selectCarreraOptions"
                                :reduce="option => option" no-options="Seleccione una opción"
                                no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                            <div v-if="errores.carrera_id" class="form-text text-danger">
                                {{ errores.carrera_id[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="ano" class="form-label">Año</label>
                            <input v-model="Seguimiento.ano" type="text" class="form-control" id="ano"
                                aria-describedby="Año" @input="validarAno">
                            <div v-if="errores.ano" class="form-text text-danger">
                                {{ errores.ano[0] }}
                            </div>
                        </div>
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

        <div class="container" ref="pdfContent"
            style="visibility: hidden; position: absolute; left: -9999px; width: 210mm;">
            <div class="text-center mb-3">
                <h5 class="mb-0">GOBIERNO DEL ESTADO DE MÉXICO</h5>
                <h5 class="mb-0">SECRETARÍA DE EDUCACIÓN</h5>
                <h5 class="mb-0">SUBSECRETARÍA DE EDUCACIÓN MEDIA SUPERIOR Y SUPERIOR</h5>
                <h4 class="mt-3"><strong>REGISTRO DE FALTAS DE ASISTENCIA, CALIFICACIONES Y PROMEDIO POR
                        ASIGNATURA</strong>
                </h4>
            </div>

            <!-- Información general -->
            <table class="table table-bordered table-sm mb-3">
                <tbody>
                    <tr>
                        <td><strong>GENERAL DE EDUCACIÓN MEDIA SUPERIOR</strong></td>
                        <td><strong>MESES</strong></td>
                        <td><strong>No. DE HORAS IMPARTIDAS</strong></td>
                    </tr>
                    <tr>
                        <td rowspan="6"><strong>DE BACHILLERATO TECNOLÓGICO</strong><br><strong>CBT, AMANALCO DE
                                BECERRA</strong></td>
                        <td>FEBRERO</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>MARZO</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>ABRIL</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>MAYO</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>JUNIO</td>
                        <td>21</td>
                    </tr>
                    <tr>
                        <td>JULIO</td>
                        <td>12</td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>TOTAL DE HORAS SEMESTRALES</strong></td>
                        <td><strong>116</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Estadísticas del plantel -->
            <table class="table table-bordered table-sm mb-3">
                <tbody>
                    <tr>
                        <td><strong>DOMICILIO CONOCIDO S/N</strong></td>
                        <td><strong>EL POTRERO</strong></td>
                        <td><strong>No. DE ALUMNOS INSCRITOS</strong></td>
                        <td colspan="3">{{ estadisticas.total_inscritos }}</td>
                    </tr>
                    <tr>
                        <td><strong>AMANALCO</strong></td>
                        <td><strong>7228353322</strong></td>
                        <td><strong>BAJAS DURANTE EL AÑO</strong></td>
                        <td colspan="3">{{ estadisticas.bajas }}</td>
                    </tr>
                    <tr>
                        <td><strong>LÓPEZ SANTANA SEBASTIAN</strong></td>
                        <td><strong>7228353322</strong></td>
                        <td><strong>EXISTENCIA AL FINAL DEL AÑO</strong></td>
                        <td colspan="3">{{ estadisticas.existencia_final }}</td>
                    </tr>
                    <tr>
                        <td><strong>SAN LUCAS, ArmandoOMEx</strong></td>
                        <td><strong>San Lucas 2da secc.</strong></td>
                        <td><strong>No. DE APROBADOS</strong></td>
                        <td colspan="3">{{ estadisticas.aprobados }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>No. REPROBADOS</strong></td>
                        <td colspan="3">{{ estadisticas.reprobados }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>% DE ALUMNOS APROBADOS</strong></td>
                        <td colspan="3">{{ estadisticas.porcentaje_aprobados }}%</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>% DE ALUMNOS REPROBADOS</strong></td>
                        <td colspan="3">{{ estadisticas.porcentaje_reprobados }}%</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>SUMA DE CALIFICACIONES</strong></td>
                        <td colspan="3">{{ estadisticas.suma_calificaciones }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>PROMEDIO DE CALIFICACIONES</strong></td>
                        <td colspan="3">{{ estadisticas.promedio_general }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Tabla de alumnos -->
            <h6 class="text-center mb-2"><strong>SUBMÓDULO II: DISEÑA Y ADMINISTRA BASES DE DATOS SIMPLES</strong></h6>
            <table class="table table-bordered table-sm mb-4">
                <thead>
                    <tr>
                        <th rowspan="2">No. DE LISTA</th>
                        <th rowspan="2">NOMBRE DEL ALUMNO (A)</th>
                        <th :colspan="parciales.length">FALTAS DE ASISTENCIA</th>
                        <th rowspan="2">TOT. DE FALTAS</th>
                        <th rowspan="2">% DE INASISTENCIA</th>
                        <th :colspan="parciales.length">EVALUACIONES</th>
                        <th rowspan="2">PROMEDIO</th>
                        <th rowspan="2">OBSERVACIONES</th>
                    </tr>
                    <tr>
                        <th v-for="(parcial, i) in parciales.length">
                            {{ `${i + 1}a` }}
                        </th>
                        <th v-for="(parcial, index) in parciales.length">
                            {{ `${index + 1}a` }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="alumno in alumnos" :key="alumno.No_DE_LISTA">
                        <td>{{ alumno.No_DE_LISTA }}</td>
                        <td>{{ alumno.NOMBRE_DEL_ALUMNO }}</td>
                        <td v-for="(parcial, index) in parciales">
                            {{ alumno[`faltas_${parcial}`] || '' }}
                        </td>
                        <!-- <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td> -->
                        <td>{{ alumno.TOT_DE_FALTAS }}</td>
                        <td>{{ alumno.PORC_INASISTENCIA }}%</td>
                        <td v-for="(parcial, index) in parciales">
                            {{ alumno[`eval_${parcial}`] || '' }}
                        </td>
                        <td>{{ alumno.PROMEDIO || '' }}</td>
                        <td>{{ alumno.OBSERVACIONES || '' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Firmas -->
            <div class="firmas-container mt-5">
                <div class="text-center mb-3">
                    <p>El Potrero, Amanalco, México {{ new Date().toLocaleDateString('es-MX') }}</p>
                </div>
                <div class="row mt-4">
                    <div class="col text-center">
                        <p class="mb-0"><strong>LÓPEZ SANTANA SEBASTIAN</strong></p>
                        <p class="mt-0">DOCENTE</p>
                    </div>
                    <div class="col text-center">
                        <p class="mb-0"><strong>REVISÓ</strong></p>
                        <p class="mb-0"><strong>AMOREA GUADALUPE HERNÁNDEZ HERNÁNDEZ</strong></p>
                        <p class="mt-0">ORIENTADOR</p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col text-center">
                        <p class="mb-0"><strong>AUTORIZÓ</strong></p>
                        <p class="mb-0"><strong>MAÑA DEL CARMEN ASSENETH VELÁZQUEZ LÓPEZ</strong></p>
                        <p class="mt-0">SUBDIRECTORA ESCOLAR</p>
                    </div>
                    <div class="col text-center">
                        <p class="mb-0"><strong>GADIEL RECILLAS MIRANDA</strong></p>
                        <p class="mt-0">DIRECTOR ESCOLAR</p>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col text-center">
                        <p class="mb-0"><strong>VALIDACIÓN PARA CAPTURA EN SISTEMA</strong></p>
                        <p class="mb-0"><strong>LUIS GONZÁLEZ CALIXTO</strong></p>
                        <p class="mt-0">SECRETARIO ESCOLAR</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import api from '../../../services/api';
import Modal from '../../../components/Modal.vue';

import html2pdf from 'html2pdf.js';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

import { mapGetters } from 'vuex';
import debounce from 'lodash/debounce';

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: 'Seguimiento',
    components: {
        EasyDataTable,
        Modal,
        vSelect
    },
    data() {
        return {
            headers: [
                { text: 'Id', value: 'id' },
                { text: 'Profesores', value: 'profesores' },
                { text: 'Materias', value: 'materias' },
                { text: 'Semestres', value: 'semestres' },
                { text: 'Grados/Grupos', value: 'grupos' },
                { text: 'Carreras', value: 'carreras' },
                { text: 'Año', value: 'ano' },
                { text: 'Acciones', value: 'action' },
            ],
            Seguimientos: [],
            cargando: false,
            Seguimiento: {
                id: null,
                materia_id: null,
                semestre_id: null,
                grupo_id: null,
                carrera_id: null,
                profesor_id: null,
                ano: null,
            },
            busqueda: '',
            errores: {},

            selectProfesor: null,
            selectOptionsProfesor: [],
            selectLoading: false,

            selectMateria: null,
            selectOptionsMateria: [],
            selectLoadingMateria: false,

            selectSemestre: null,
            selectOptionsSemestre: [],
            selectLoadingSemestre: false,

            selectGrupo: null,
            selectOptionsGrupo: [],
            selectLoadingGrupo: false,

            selectCarrera: null,
            selectOptionsCarrera: [],
            selectLoadingCarrera: false,
            //pdf
            alumnos: [],
            parciales: [],
            estadisticas: {},
            seguimientoId: 1
        }
    },
    watch: {
        busqueda: {
            // 👀 Observa cada cambio en la búsqueda
            handler: debounce(function (val) {
                this.consultar(val);
            }, 300),
            immediate: true
        },
        selectedYear(newYear, oldYear) {
            this.consultar(this.busqueda);
        }
    },
    computed: {
        ...mapGetters(['selectedYear']), // trae selectedYear del store
    },
    methods: {
        async consultar(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get('/Registro/Seguimiento', {
                    params: {
                        search: filtro,
                        ano: this.selectedYear
                    }
                });
                this.Seguimientos = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Seguimiento = this.selectOptionNull();
            this.selectMateria = null;
            this.selectSemestre = null;
            this.selectGrupo = null;
            this.selectCarrera = null;
            this.selectProfesor = null;
            this.Seguimiento.ano = this.selectedYear;
            this.$refs.modalSeguimiento.abrir();
        },
        async agregar() {
            try {
                this.Seguimiento.materia_id = this.selectMateria?.id;
                this.Seguimiento.semestre_id = this.selectSemestre?.id;
                this.Seguimiento.grupo_id = this.selectGrupo?.id;
                this.Seguimiento.carrera_id = this.selectCarrera?.id;
                this.Seguimiento.profesor_id = this.selectProfesor?.id;
                await api.post('/Registro/Seguimiento', this.Seguimiento);
                this.consultar();
                this.$refs.modalSeguimiento.cerrar();

                this.Seguimiento = this.selectOptionNull();
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
        editar(id) {
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Seguimientos.find(p => p.id === id);
            console.log(encontrado);
            if (encontrado) {
                this.Seguimiento = { ...encontrado };
                this.selectMateria = {
                    id: this.Seguimiento.materia_id,
                    text: this.Seguimiento.materias
                };
                this.selectSemestre = {
                    id: this.Seguimiento.semestre_id,
                    text: this.Seguimiento.semestres
                };
                this.selectGrupo = {
                    id: this.Seguimiento.grupo_id,
                    text: this.Seguimiento.grupos
                };
                this.selectCarrera = {
                    id: this.Seguimiento.carrera_id,
                    text: this.Seguimiento.carreras
                };
                this.selectProfesor = {
                    id: this.Seguimiento.profesor_id,
                    text: this.Seguimiento.profesores
                };
                this.$refs.modalSeguimiento.abrir();
            }
        },
        async guardarCambios(id) {
            try {
                this.Seguimiento.materia_id = this.selectMateria.id;
                this.Seguimiento.semestre_id = this.selectSemestre.id;
                this.Seguimiento.grupo_id = this.selectGrupo.id;
                this.Seguimiento.carrera_id = this.selectCarrera.id;
                this.Seguimiento.profesor_id = this.selectProfesor.id;
                await api.put(`/Registro/Seguimiento/${id}`, this.Seguimiento);

                this.consultar();
                this.$refs.modalSeguimiento.cerrar();

                this.Seguimiento = this.selectOptionNull();
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
        },
        validarAno() {
            const ano = this.Seguimiento.ano;
            const erroresAno = [];
            const soloNumeros = /^[0-9]*$/;
            if (!soloNumeros.test(ano.toString())) {
                this.Seguimiento.ano = this.Seguimiento.ano.replace(/\D/g, '');
                erroresAno.push('El año solo debe contener números.');
                // Elimina todo lo que no sea número
            }
            if (ano.toString().length > 4) {
                this.Seguimiento.ano = this.Seguimiento.ano.slice(0, 4);
                erroresAno.push('El año no debe exceder los 4 dígitos.');
            }
            this.errores.ano = erroresAno.length ? erroresAno : [];
        },
        selectOptionNull() {
            return {
                id: null,
                carrera_id: null,
                carreras: null,
                grupo_id: null,
                grupos: null,
                materia_id: null,
                materias: null,
                profesor_id: null,
                profesores: null,
                semestre_id: null,
                semestres: null,
                ano: null,
            };
        },
        selectProfesorOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoading = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Profesor`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsProfesor = [
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
                    this.selectOptionsProfesor = [];
                } finally {
                    this.selectLoading = false
                }
            }, 300)
        },
        // selectMateriaOptions
        selectMateriaOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingMateria = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Materia`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsMateria = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsMateria = [];
                } finally {
                    this.selectLoadingMateria = false
                }
            }, 300)
        },
        // selectSemestreOptions
        selectSemestreOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingSemestre = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Semestre`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsSemestre = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsSemestre = [];
                } finally {
                    this.selectLoadingSemestre = false
                }
            }, 300)
        },
        // selectGrupoOptions
        selectGrupoOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingGrupo = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Grupo`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsGrupo = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsGrupo = [];
                } finally {
                    this.selectLoadingGrupo = false
                }
            }, 300)
        },
        // selectCarreraOptions
        selectCarreraOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingCarrera = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Carrera`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsCarrera = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsCarrera = [];
                } finally {
                    this.selectLoadingCarrera = false
                }
            }, 300)
        },
        parcialesObjeto(parciales) {
            if (!Array.isArray(parciales)) return {};
            return parciales.map(item => {
                // Reemplaza espacios por "_" y asegura el formato correcto
                return typeof item === 'string'
                    ? item.replace(/\s+/g, '_')
                    : item;
            });
        },
        async formato1(id) {
            try {
                const response = await axios.get(`/api/Registro/Seguimiento/${id}/formato1`);
                this.alumnos = response.data.alumnos;
                this.estadisticas = response.data.estadisticas;
                this.parciales = this.parcialesObjeto(response.data.parciales);

                const element = this.$refs.pdfContent;
                element.style.visibility = 'visible';
                element.style.position = 'static';
                element.style.left = '0';

                const options = {
                    margin: 0.5,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                await html2pdf().set(options).from(element).save();

                element.style.visibility = 'hidden';
                element.style.position = 'absolute';
                element.style.left = '-9999px';
            } catch (error) {
                console.error('Error al generar Formato 1:', error);
            }
        }

    },
    mounted() {
        this.consultar();
    }
}
</script>

<style>
.vs__selected {
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.reporte-container {
    font-family: Arial, sans-serif;
    font-size: 10pt;
    line-height: 1.2;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9pt;
}

.table th,
.table td {
    border: 1px solid #000;
    padding: 3px;
    text-align: center;
}

.table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.text-center {
    text-align: center;
}

.firmas-container {
    margin-top: 30px;
}

.firmas-container p {
    margin-bottom: 5px;
}

.mt-0 {
    margin-top: 0;
}

.mb-0 {
    margin-bottom: 0;
}
</style>