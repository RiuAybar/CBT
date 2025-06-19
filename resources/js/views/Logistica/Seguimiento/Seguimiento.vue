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

    </div>
</template>

<script>
import api from '../../../services/api';
import Modal from '../../../components/Modal.vue';

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
        ...mapGetters(['selectedYear']) // trae selectedYear del store
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
</style>