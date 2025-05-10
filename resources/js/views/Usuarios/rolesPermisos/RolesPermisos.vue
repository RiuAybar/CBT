<template>
    <main class="content">
        <div class="container-fluid p-0">
            <button @click="crearPermiso()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Permiso
            </button>
            <h1 class="h3 mb-3">
                Rol
                <strong>
                    {{ Rol.name }}
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title">
                                        Lista de Permisos
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Permisos" :loading="cargando" :rows-per-page="5"
                            table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template #item-action="{ id, name }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-danger" @click="eliminarPermiso(id, name)">
                                        Eliminar
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>



import api from '../../../services/api';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

export default {
    name: 'RolesPermisos',
    components: {
        EasyDataTable,
    },
    data() {
        return {
            cargando: true,
            Rol: {
                id: null,
                name: null
            },
            headers: [
                { text: 'Id', value: 'id' },
                { text: 'Nombre', value: 'name' },
                { text: 'Acciones', value: 'action' },
            ],
            Permisos: [],
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
        async consultarRol() {
            try {
                const res = await api.get(`/Role/${this.$route.params.id}`);
                this.Rol = res.data;
            } catch (error) {
                if (error.response && error.response.status === 404) {
                    this.$swal.fire('Error', '❌ El rol no existe', 'error');
                    this.$router.replace({ name: 'roles' }); // Redirige a la lista de roles
                } else {
                    console.error(error);
                }
            } finally {
                this.cargando = false;
            }
        },

        async consultarPermisos(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get(`/Role/${this.$route.params.id}/Permiso`, {
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
        },
        eliminarPermiso(id, nombre) {
            this.$swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el registro ${nombre}, ${id}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // 🚨 Petición DELETE a API
                    try {
                        await api.delete(`/Role/${this.$route.params.id}/destroy`, {
                            data: {
                                Permiso: id
                            }
                        });
                        this.consultarPermisos();
                        this.$swal.fire('Éxito', `✅ Registro ${nombre} eliminado correctamente`, 'success');
                        this.errores = {}; // 🔄 Limpia los errores
                    } catch (error) {
                        if (error.response && error.response.status === 422) {
                            this.errores = error.response.data.errors;
                        } else {
                            console.error(error);
                            this.$swal.fire('Error', '❌ No se pudo eliminar el registro', 'error');
                        }
                    }
                }
            });
        },
    },
    mounted() {
        this.consultarPermisos();
        this.consultarRol();
    }
}
</script>

<style></style>