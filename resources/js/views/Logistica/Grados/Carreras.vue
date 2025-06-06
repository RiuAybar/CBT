<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Carrera
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Carreras
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Carreras
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Carreras" :loading="cargando" :rows-per-page="5"
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

        <Modal size="lg" ref="modalCarreras" id="modal-carrera"
            :title="Carrera.id ? 'Editar Carrera' : 'Agregar Carrera'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Carrera</label>
                        <input v-model="Carrera.nombre" type="text" class="form-control" id="nombre"
                            aria-describedby="Nombre">
                        <div v-if="errores.nombre" class="form-text text-danger">
                            {{ errores.nombre[0] }}
                        </div>
                    </div>
                </form>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Carrera.id" class="btn btn-success" @click="guardarCambios(Carrera.id)">
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


export default {
    name: 'Carreras',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {
            headers: [
                { text: 'Id', value: 'id' },
                { text: 'Nombre', value: 'nombre' },
                { text: 'Acciones', value: 'action' },
            ],
            Carreras: [],
            cargando: false,
            Carrera: {
                id: null,
                nombre: '',
            },
            busqueda: '',
            errores: {},
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
                const res = await api.get('/Logistica/Carrera', {
                    params: { search: filtro }
                });
                this.Carreras = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Carrera = {
                id: null,
                nombre: '',
            };
            this.$refs.modalCarreras.abrir();
        },

        async agregar() {
            try {
                await api.post('/Logistica/Carrera', this.Carrera);
                this.consultar();
                this.$refs.modalCarreras.cerrar();

                this.Carrera = {
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
            const encontrado = this.Carreras.find(p => p.id === id);
            if (encontrado) {
                this.Carrera = { ...encontrado };
                this.$refs.modalCarreras.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                await api.put(`/Logistica/Carrera/${id}`, this.Carrera);

                this.consultar();
                this.$refs.modalCarreras.cerrar();

                this.Carrera = {
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