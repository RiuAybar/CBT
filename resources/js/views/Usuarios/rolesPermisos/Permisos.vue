<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crearPermiso()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Permiso
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Permisos
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Permisos
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Permisos" :loading="cargando" :rows-per-page="5"
                            table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template #item-action="{ id, nombre, descripcion, precio, stock }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary"
                                        @click="editarPermiso(id)">
                                        Editar
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>

                    </div>
                </div>
            </div>
        </div> 

        <Modal size="lg" ref="modalPermiso" id="modal-permiso"
            :title="Permiso.id ? 'Editar Permiso' : 'Agregar Permiso'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Permiso</label>
                        <input v-model="Permiso.name" type="text" class="form-control" id="name"
                            aria-describedby="Nombre">
                        <div v-if="errores.name" class="form-text text-danger">
                            {{ errores.name[0] }}
                        </div>
                    </div>
                </form>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Permiso.id" class="btn btn-success" @click="guardarCambios(Permiso.id)">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Guardar Cambios
                </button>
                <button v-else class="btn btn-success" @click="agregarPermiso()">
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
    name: 'Permisos',
    components: {
        EasyDataTable,
        Modal,
    },
    data() {
        return {
            headers: [
                { text: 'Id', value: 'id' },
                { text: 'Nombre', value: 'name' },
                { text: 'Acciones', value: 'action' },
            ],
            Permisos: [],
            cargando: false,
            Permiso: {
                id: null,
                name: '',
            },
            busqueda: '',
            errores: {},
        }
    },
    watch: {
        // 👀 Observa cada cambio en la búsqueda
        busqueda: {
            handler(val) {
                this.consultarPermisos(val);
            },
            immediate: true
        }
    },
    methods: {
        async consultarPermisos(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get('/Permission', {
                    params: { search: filtro }
                });
                this.Permisos = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crearPermiso() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Permiso = {
                id: null,
                name: '',
            };
            this.$refs.modalPermiso.abrir();
        },

        async agregarPermiso() {
            try {
                await api.post('/Permission', this.Permiso);
                this.consultarPermisos();
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

        editarPermiso(id) {
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Permisos.find(p => p.id === id);
            if (encontrado) {
                this.Permiso = { ...encontrado };
                this.$refs.modalPermiso.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                await api.put(`/Permission/${id}`, this.Permiso);

                this.consultarPermisos();
                this.$refs.modalPermiso.cerrar();

                this.Permiso = {
                    id: null,
                    name: ''
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
        this.consultarPermisos();
    }
}
</script>

<style></style>