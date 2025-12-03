<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1 me-1"
                v-if="hasPermission('puede agregar escalas evaluativas')">
                <i class="bi bi-plus-circle"></i>
                Agregar Escala
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Escalas Evaluativas
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Escalas Evaluativas
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headersFiltrados" :items="EscalasEvaluativas" :loading="cargando" :rows-per-page="5"
                            table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template #item-action="{ id, nombre }" v-if="hasPermission('puede editar escalas evaluativas')">
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

        <Modal size="lg" ref="modalEEvaluativa" id="modal-Escala" :title="EscalaEvaluativa.id ? 'Editar Escala Evaluativa' : 'Agregar Escala Evaluativa'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input v-model="EscalaEvaluativa.nombre" type="text" class="form-control" id="nombre"
                            aria-describedby="Nombre">
                        <div v-if="errores.nombre" class="form-text text-danger">
                            {{ errores.nombre[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="abreviatura" class="form-label">Abreviatura</label>
                        <input v-model="EscalaEvaluativa.abreviatura" type="text" class="form-control" id="abreviatura"
                            aria-describedby="Abreviatura" maxlength="10">
                        <div v-if="errores.abreviatura" class="form-text text-danger">
                            {{ errores.abreviatura[0] }}
                        </div>
                    </div>
                </div>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="EscalaEvaluativa.id && hasPermission('puede editar escalas evaluativas')" class="btn btn-success"
                    @click="guardarCambios(EscalaEvaluativa.id)">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Guardar Cambios
                </button>
                <button v-if="!EscalaEvaluativa.id && hasPermission('puede agregar escalas evaluativas')" class="btn btn-success"
                    @click="agregar()">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Agregar
                </button>
            </template>
        </Modal>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import api from '../../../services/api';
import Modal from '../../../components/Modal.vue';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

import debounce from 'lodash/debounce';

export default {
    name: 'EscalasEvaluativas',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {
            EscalasEvaluativas: [],
            cargando: false,
            EscalaEvaluativa: {
                id: null,
                nombre: '',
                abreviatura: '',
            },
            busqueda: '',
            errores: {},
        }
    },
    computed: {
        ...mapGetters('auth', ['hasPermission']),
        headersFiltrados() {
            const base = [
                { text: 'Id', value: 'id' },
                { text: 'Nombre', value: 'nombre' },
                { text: 'Abreviatura', value: 'abreviatura' },
            ];
            if (this.hasPermission('puede editar escalas evaluativas')) {
                base.push({ text: 'Acciones', value: 'action' });
            }
            return base;
        }
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
                const res = await api.get('/gestion/EscalaEvaluativa', {
                    params: { search: filtro }
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
            this.EscalaEvaluativa = {
                id: null,
                nombre: '',
                abreviatura: '',
            };
            this.$refs.modalEEvaluativa.abrir();
        },

        async agregar() {
            try {
                await api.post('/gestion/EscalaEvaluativa', this.EscalaEvaluativa);
                this.consultar();
                this.$refs.modalEEvaluativa.cerrar();

                this.EscalaEvaluativa = {
                    id: null,
                    nombre: '',
                    abreviatura: '',
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
            const encontrado = this.EscalasEvaluativas.find(p => p.id === id);
            if (encontrado) {
                this.EscalaEvaluativa = { ...encontrado };
                this.$refs.modalEEvaluativa.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                await api.put(`/gestion/EscalaEvaluativa/${id}`, this.EscalaEvaluativa);

                this.consultar();
                this.$refs.modalEEvaluativa.cerrar();

                this.EscalaEvaluativa = {
                    id: null,
                    nombre: '',
                    abreviatura: '',
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