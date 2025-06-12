<template>
    <div>
        <div class="container-fluid p-0">
            <button v-if="Grado?.id" @click="crear()" class="btn btn-primary float-end mt-n1 mb-1">
                <i class="bi bi-plus-circle"></i>
                Agregar Grupos
            </button>
            <router-link v-if="Grado?.id" to="/grados" class="btn btn-danger float-end mt-n1 me-1">
                <i class="bi bi-arrow-return-left"></i>
                Regresar
            </router-link>
            <h1 class="h3 mb-3">
                <strong>
                    {{ Grado?.nombre ? `Grupos de: ${Grado.nombre}` : '' }}
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Grupos
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Grupos" :loading="cargando" :rows-per-page="5"
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

        <Modal size="lg" ref="modalGrupos" id="modal-Grupo" :title="Grupo.id ? 'Editar Grupo' : 'Agregar Grupo'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Grupo</label>
                        <input v-model="Grupo.nombre" type="text" class="form-control" id="nombre"
                            aria-describedby="Nombre">
                        <div v-if="errores.nombre" class="form-text text-danger">
                            {{ errores.nombre[0] }}
                        </div>
                    </div>
                </form>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Grupo.id" class="btn btn-success" @click="guardarCambios(Grupo.id)">
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

import debounce from 'lodash/debounce';

export default {
    name: 'Grupos',
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
            Grupos: [],
            cargando: false,
            Grupo: {
                id: null,
                nombre: '',
            },
            busqueda: '',
            errores: {},
            Grado: {
                id: null,
                nombre: null
            },
        }
    },
    watch: {
        busqueda: {
            handler: debounce(function (val) {
                // 👀 Observa cada cambio en la búsqueda
                this.consultar(val);
            }, 300),
            immediate: true
        }
    },
    methods: {
        async consultarMov() {
            try {
                const res = await api.get(`/Logistica/Grado/${this.$route.params.id}`);
                this.Grado = res.data;
            } catch (error) {
                if (error.response && error.response.status === 404) {
                    this.$swal.fire('Error', '❌ El grado no existe', 'error');
                    this.$router.replace({ name: 'grados' }); // Redirige a la lista de roles
                } else {
                    console.error(error);
                }
            } finally {
                this.cargando = false;
            }
        },
        async consultar(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get(`/Logistica/Grupo/${this.$route.params.id}/index`, {
                    params: { search: filtro }
                });
                this.Grupos = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Grupo = {
                id: null,
                nombre: '',
            };
            this.$refs.modalGrupos.abrir();
        },

        async agregar() {
            try {
                this.Grupo.grado_id = this.$route.params.id;
                await api.post(`/Logistica/Grupo`, this.Grupo);
                this.consultar();
                this.$refs.modalGrupos.cerrar();

                this.Grupo = {
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
            const encontrado = this.Grupos.find(p => p.id === id);
            if (encontrado) {
                this.Grupo = { ...encontrado };
                this.$refs.modalGrupos.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                this.Grupo.grado_id = this.$route.params.id;
                await api.put(`/Logistica/Grupo/${id}`, this.Grupo);
                this.consultar();
                this.$refs.modalGrupos.cerrar();

                this.Grupo = {
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
        this.consultarMov();
    }
}
</script>

<style></style>