<template>
    <div class="container mt-5">
        <h2 class="text-center">
            Lista de usuarios
        </h2>
        <div class="row">
            <div class="col-sm-6">
                <button @click="mostrarModal" class="btn btn-success">Agregar Usuarios</button>
            </div>
            <div class="col-sm-6 mb-3">
                <input class="form-control" v-model="busqueda" type="text">
            </div>

            <EasyDataTable :headers="headers" :items="usuarios" :loading="cargando" :rows-per-page="5" border-cell
                :text="textosTabla" buttons-pagination>

                <template #item-accion="item">
                    <div class="btn-group">
                        <button @click="editarUser(item.id)" class="btn btn-info me-2">Editar</button>
                        <button @click="eliminarUser(item.id)" class="btn btn-danger">Eliminar</button>
                    </div>
                </template>

            </EasyDataTable>

            <Modal ref="modalComponente" id="mostrarUsuarios" title="Mostrar usuarios">
                <template #default>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input v-model="usuario.name" type="text" id="name" class="form-control"
                                placeholder="Nombre">
                            <div v-if="errores.name" class="form-text text-danger">
                                {{ errores.name[0] }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input v-model="usuario.email" type="email" id="email" class="form-control"
                                placeholder="correo@correo.com">
                            <div v-if="errores.email" class="form-text text-danger">
                                {{ errores.email[0] }}
                            </div>
                        </div>
                    </form>
                </template>

                <template #footer>
                    <button v-if="usuario.id" @click="updateUser(usuario.id)" class="btn btn-success">Actualizar
                        usuario</button>
                    <button v-else @click="agregarUsuario" class="btn btn-success">Agregar usuario</button>
                </template>

            </Modal>


        </div>
    </div>
</template>

<script>
import api from '../../services/api';
import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';
import Modal from '../../components/Modal.vue';
import debounce from 'lodash/debounce';


export default {
    name: 'Usuarios',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {
            busqueda: '',
            headers: [
                { text: 'Nombre', value: 'name', sortable: true },
                { text: 'Email', value: 'email', sortable: true },
                { text: 'Acciones', value: 'accion', sortable: false },
            ],
            usuarios: [],
            usuario: {
                id: null,
                name: '',
                email: '',
            },
            cargando: false,
            textosTabla: {
                rowsPerPageText: 'Filas por página:',
                pageText: 'Página {0} de {1}',
                noDataText: 'No hay datos disponibles',
            },
            errores: {},
        }
    },
    watch: {
        busqueda: {
            handler: debounce(function (val) {
                this.obtenerUsuarios(val);
            }, 300),
            immediate: true
        }
    },
    methods: {
        async obtenerUsuarios(val = '') {
            this.cargando = true;
            try {
                const res = await api.get('/usuarios', {
                    params: { search: val }
                });
                this.usuarios = res.data;
            } catch (error) {
                console.error(error);
                this.$swal.fire('Error', 'consulet al administrador', 'error');
            } finally {
                this.cargando = false;
            }
        },
        mostrarModal() {
            this.usuario = {
                id: null,
                name: '',
                email: '',
            };
            this.errores = {};
            this.$refs.modalComponente.abrir();
        },
        async agregarUsuario() {
            try {
                const res = await api.post('/usuarios', this.usuario);
                this.obtenerUsuarios();
                this.errores = {};
                this.$refs.modalComponente.cerrar();
                this.$swal.fire('Exito', 'Se agrego correctamente el registro.', 'success')
            } catch (error) {
                if (error.response && error.response.status == 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', 'No se agrego el registro, conulte con el administrador', 'error');
                }

            }
        },
        editarUser(id) {
            try {
                const encontrado = this.usuarios.find(e => e.id == id);
                if (encontrado) {
                    this.usuario = { ...encontrado };
                }
                this.$refs.modalComponente.abrir();
            } catch (error) {
                this.$swal.fire('Error', 'Consulte al administrador', 'error');
            }
        },
        async updateUser(id) {
            try {
                await api.put(`/usuarios/${id}`, this.usuario);
                this.obtenerUsuarios();
                this.errores = {};
                this.$refs.modalComponente.cerrar();
                this.$swal.fire('Exito', 'Se actualizo correctamente el registro', 'success');
            } catch (error) {
                if (error.response && error.response.status == 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', 'Consulte al administrador', 'error');
                }
            }
        },
        async eliminarUser(id) {
            this.$swal.fire({
                title: "¿Está seguro?",
                text: "¡No podrás revertir esto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, ¡eliminar!",
                cancelButtonText: "Cancelar"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await api.delete(`/usuarios/${id}`);
                        this.obtenerUsuarios();
                        this.$swal.fire({
                            title: "¡Eliminado!",
                            text: "Su registro ha sido eliminado",
                            icon: "success"
                        });
                    } catch (error) {
                        console.error(error);
                        this.$swal.fire('Error', 'Consulte al administrador', 'error');
                    }

                }
            });

        }
    }
}
</script>
<style></style>