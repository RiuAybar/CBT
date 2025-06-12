<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crearRol()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Roles
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Roles
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Roles
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Roles" :loading="cargando" :rows-per-page="5"
                            table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template #item-action="{ id, nombre, descripcion, precio, stock }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary me-1" @click="editarRol(id)">
                                        Editar
                                    </button>
                                    <router-link :to="`/roles/${id}/edit`" class="btn btn-sm btn-outline-warning">
                                        Agregar Permisos
                                    </router-link>
                                </div>
                            </template>
                        </EasyDataTable>

                    </div>
                </div>
            </div>
        </div>

        <Modal size="lg" ref="modalRol" id="modal-rol"
            :title="Rol.id ? 'Editar Rol' : 'Agregar Rol'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Rol</label>
                        <input v-model="Rol.name" type="text" class="form-control" id="name"
                            aria-describedby="Nombre">
                        <div v-if="errores.name" class="form-text text-danger">
                            {{ errores.name[0] }}
                        </div>
                    </div>
                </form>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Rol.id" class="btn btn-success" @click="guardarCambios(Rol.id)">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Guardar Cambios
                </button>
                <button v-else class="btn btn-success" @click="agregarRol()">
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

import debounce from 'lodash/debounce';


export default {
    name: 'Roles',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {
            headers: [
                { text: 'Id', value: 'id' },
                { text: 'Nombre', value: 'name' },
                { text: 'Acciones', value: 'action' },
            ],
            Roles: [],
            cargando: false,
            Rol: {
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
            handler: debounce(function (val) {
                this.consultarRoles(val);
            }, 300),
            immediate: true
        }
    },
    methods: {
        async consultarRoles(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get('/Role', {
                    params: { search: filtro }
                });
                this.Roles = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crearRol() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Rol = {
                id: null,
                name: '',
            };
            this.$refs.modalRol.abrir();
        },

        async agregarRol() {
            try {
                await api.post('/Role', this.Rol);
                this.consultarRoles();
                this.$refs.modalRol.cerrar();

                this.Rol = {
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

        editarRol(id) {
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Roles.find(p => p.id === id);
            if (encontrado) {
                this.Rol = { ...encontrado };
                this.$refs.modalRol.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                await api.put(`/Role/${id}`, this.Rol);

                this.consultarRoles();
                this.$refs.modalRol.cerrar();

                this.Rol = {
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
        this.consultarRoles();
    }
}
</script>

<style></style>