<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Registro de meses y horas.
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de meses y horas.
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="MesesHoras" :loading="cargando" :rows-per-page="5"
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

        <Modal size="lg" ref="modalHorasMeses" id="modal-meses-horas"
            :title="MesHora.id ? 'Editar mes y hora' : 'Agregar mes y hora'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="row">
                        <div class="mb-3 col-sm-4">
                            <label for="mes" class="form-label">Mes</label>

                            <v-select :options="meses" v-model="mesSeleccionado" label="nombre"
                                placeholder="Selecciona un mes" />
                            <div v-if="errores.mes" class="form-text text-danger">
                                {{ errores.mes[0] }}
                            </div>
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="horasImpartidas" class="form-label">Horas Impartidas</label>
                            <input v-model="MesHora.horasImpartidas" type="text" class="form-control"
                                id="horasImpartidas" aria-describedby="Horas Impartidas">
                            <div v-if="errores.horasImpartidas" class="form-text text-danger">
                                {{ errores.horasImpartidas[0] }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="carrera_id" class="form-label">Carrera</label>
                            <v-select v-model="selectSelected" :options="selectOptions" label="text" :filterable="false"
                                :loading="selectLoading" placeholder="Seleccione una materia"
                                @search="selectFetchOptions" :reduce="option => option"
                                no-options="Seleccione una opción" no-results="No se encontraron resultados"
                                :selectable="option => !option.disabled" />
                            <div v-if="errores.carrera_id" class="form-text text-danger">
                                {{ errores.carrera_id[0] }}
                            </div>
                        </div>
                    </div>
                </form>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="MesHora.id" class="btn btn-success" @click="guardarCambios(MesHora.id)">
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

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';


export default {
    name: 'HorasDocente',
    components: {
        EasyDataTable,
        Modal,
        vSelect,
    },
    data() {
        return {
            headers: [
                { text: 'Id', value: 'id' },
                { text: 'Mes', value: 'mes' },
                { text: 'Horas Impartidas', value: 'horasImpartidas' },
                { text: 'Carrera', value: 'carrera.text' },
                { text: 'Acciones', value: 'action' },
            ],
            MesesHoras: [],
            cargando: false,
            MesHora: {
                id: null,
                mes: '',
                horasImpartidas: ''
            },
            busqueda: '',
            errores: {},

            mesSeleccionado: null,
            meses: this.generarMeses(),

            selectSelected: null,
            selectOptions: [
                {
                    id: null,
                    text: 'Seleccione una opción',
                    disabled: true
                }
            ],
            selectLoading: false,

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
                const res = await api.get('/Logistica/RegistroHorasDocencia', {
                    params: { search: filtro }
                });
                this.MesesHoras = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.MesHora = {
                id: null,
                mes: '',
                horasImpartidas: ''
            };
            this.mesSeleccionado = null;
            this.selectSelected = {
                id: null,
                text: null,
            };
            this.$refs.modalHorasMeses.abrir();
        },

        async agregar() {
            try {
                // console.log(this.mesSeleccionado);
                // Asigna los valores seleccionados antes de enviar
                this.MesHora.mes = this.mesSeleccionado?.valor || '';
                this.MesHora.carrera_id = this.selectSelected?.id || null;
                
                await api.post('/Logistica/RegistroHorasDocencia', this.MesHora);
                this.consultar();
                this.$refs.modalHorasMeses.cerrar();

                this.MesHora = {
                    id: null,
                    mes: '',
                    horasImpartidas: ''
                };
                this.mesSeleccionado = null; // 🔄 Limpia select
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
            const encontrado = this.MesesHoras.find(p => p.id === id);
            if (encontrado) {
                this.MesHora = { ...encontrado };
                this.mesSeleccionado = {
                    nombre: this.MesHora.mes,
                    valor: this.MesHora.mes,
                };
                this.selectSelected = { ...this.MesHora.carrera };
                this.$refs.modalHorasMeses.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                // console.log(this.mesSeleccionado);
                // Asigna los valores seleccionados antes de enviar
                this.MesHora.mes = this.mesSeleccionado?.valor || '';
                this.MesHora.carrera_id = this.selectSelected?.id || null;

                await api.put(`/Logistica/RegistroHorasDocencia/${id}`, this.MesHora);
                this.consultar();
                this.$refs.modalHorasMeses.cerrar();

                this.MesHora = {
                    id: null,
                    mes: '',
                    horasImpartidas: ''
                };
                this.mesSeleccionado
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

        generarMeses() {
            return Array.from({ length: 12 }, (_, i) => {
                const nombreMes = new Intl.DateTimeFormat('es-ES', { month: 'long' }).format(new Date(2023, i, 1))
                return {
                    nombre: nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1),
                    valor: nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1)
                }
            })
        },

        selectFetchOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoading = true
                try {
                    const response = await api.get(`/Logistica/RegistroHorasDocencia/1`, {
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
                            text: item.nombre
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