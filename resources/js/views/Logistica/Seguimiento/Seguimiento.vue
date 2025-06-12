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
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
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
                            <label for="Profesor" class="form-label">Profesor</label>
                            <v-select v-model="selectProfesor" :options="selectOptionsProfesor" label="text"
                                :filterable="false" :loading="selectLoading" placeholder="Seleccione un profesor"
                                @search="selectProfesorOptions" :reduce="option => option"
                                no-options="Seleccione una opción" no-results="No se encontraron resultados"
                                :selectable="option => !option.disabled" />
                            <!-- <div v-if="errores.rol" class="form-text text-danger">
                                {{ errores.rol[0] }}
                            </div> -->
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="nombre" class="form-label">Seguimiento</label>
                            <input v-model="Seguimiento.nombre" type="text" class="form-control" id="nombre"
                                aria-describedby="Nombre">
                            <div v-if="errores.nombre" class="form-text text-danger">
                                {{ errores.nombre[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="nombre" class="form-label">Seguimiento</label>
                            <input v-model="Seguimiento.nombre" type="text" class="form-control" id="nombre"
                                aria-describedby="Nombre">
                            <div v-if="errores.nombre" class="form-text text-danger">
                                {{ errores.nombre[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="nombre" class="form-label">Seguimiento</label>
                            <input v-model="Seguimiento.nombre" type="text" class="form-control" id="nombre"
                                aria-describedby="Nombre">
                            <div v-if="errores.nombre" class="form-text text-danger">
                                {{ errores.nombre[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="nombre" class="form-label">Seguimiento</label>
                            <input v-model="Seguimiento.nombre" type="text" class="form-control" id="nombre"
                                aria-describedby="Nombre">
                            <div v-if="errores.nombre" class="form-text text-danger">
                                {{ errores.nombre[0] }}
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
                { text: 'Grupos', value: 'grupos' },
                { text: 'Carreras', value: 'carreras' },
                { text: 'Año', value: 'ano' },
                { text: 'Acciones', value: 'action' },
            ],
            Seguimientos: [],
            cargando: false,
            Seguimiento: {
                id: null,
                ano: '',
            },
            busqueda: '',
            errores: {},

            selectProfesor: null,
            selectOptionsProfesor: [{
                id: null,
                text: 'Seleccione una opción',
                disabled: true
            }],
            selectLoading: false,
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
            this.Seguimiento = {
                id: null,
                nombre: '',
            };
            this.$refs.modalSeguimiento.abrir();
        },
        async agregar() {
            try {
                await api.post('/Logistica/Semestre', this.Seguimiento);
                this.consultar();
                this.$refs.modalSeguimiento.cerrar();

                this.Seguimiento = {
                    id: null,
                    nombre: ''
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
        editar(id) {
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Seguimientos.find(p => p.id === id);
            if (encontrado) {
                this.Seguimiento = { ...encontrado };
                this.$refs.modalSeguimiento.abrir();
            }
        },
        async guardarCambios(id) {
            try {
                await api.put(`/Logistica/Semestre/${id}`, this.Seguimiento);

                this.consultar();
                this.$refs.modalSeguimiento.cerrar();

                this.Seguimiento = {
                    id: null,
                    nombre: ''
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
                text: 'Seleccione una opción',
                disabled: true
            };
        },
        selectProfesorOptions(search) {
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
        // this.selectOptionsProfesor = [this.selectOptionNull()];
    }
}
</script>

<style></style>