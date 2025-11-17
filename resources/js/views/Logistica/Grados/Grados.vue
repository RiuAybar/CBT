<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1 me-1"
                v-if="hasPermission('Puede agregar grados')">
                <i class="bi bi-plus-circle"></i>
                Agregar Grados
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Grados
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Grados
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headersFiltrados" :items="Grados" :loading="cargando" :rows-per-page="5"
                            table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template #item-action="{ id, nombre }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary me-1" @click="editar(id)"
                                        v-if="hasPermission('Puede editar grados')">
                                        Editar
                                    </button>
                                    <router-link :to="`/grupos/${id}/edit`" class="btn btn-sm btn-outline-warning"
                                        v-if="hasPermission('ver grupos')">
                                        Agregar Grupos
                                    </router-link>
                                </div>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>

        <Modal size="lg" ref="modalGrados" id="modal-Grado" :title="Grado.id ? 'Editar Grado' : 'Agregar Grado'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Grado</label>
                    <input v-model="Grado.nombre" type="text" class="form-control" id="nombre"
                        aria-describedby="Nombre">
                    <div v-if="errores.nombre" class="form-text text-danger">
                        {{ errores.nombre[0] }}
                    </div>
                </div>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Grado.id && hasPermission('Puede editar grados')" class="btn btn-success"
                    @click="guardarCambios(Grado.id)">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Guardar Cambios
                </button>
                <button v-if="!Grado.id && hasPermission('Puede agregar grados')" class="btn btn-success"
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
    name: 'Grados',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {
            Grados: [],
            cargando: false,
            Grado: {
                id: null,
                nombre: '',
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
            ];
            if (this.hasPermission('ver grupos') || this.hasPermission('Puede editar grados')) {
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
                const res = await api.get('/Logistica/Grado', {
                    params: { search: filtro }
                });
                this.Grados = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Grado = {
                id: null,
                nombre: '',
            };
            this.$refs.modalGrados.abrir();
        },

        async agregar() {
            try {
                await api.post('/Logistica/Grado', this.Grado);
                this.consultar();
                this.$refs.modalGrados.cerrar();

                this.Grado = {
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
            console.log(id, this.Grados);
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Grados.find(p => p.id === id);
            if (encontrado) {
                this.Grado = { ...encontrado };
                this.$refs.modalGrados.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                await api.put(`/Logistica/Grado/${id}`, this.Grado);

                this.consultar();
                this.$refs.modalGrados.cerrar();

                this.Grado = {
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
        }
    },
    mounted() {
        this.consultar();
    }
}
</script>

<style></style>