<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Materias
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Materias
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Materias
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headers" :items="Materias" :loading="cargando" :rows-per-page="5"
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

        <Modal size="lg" ref="modalMaterias" id="modal-Materias"
            :title="Materia.id ? 'Editar Materia' : 'Agregar Materia'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <!-- <form> -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Materia</label>
                        <input v-model="Materia.nombre" type="text" class="form-control" id="nombre"
                            aria-describedby="Nombre">
                        <div v-if="errores.nombre" class="form-text text-danger">
                            {{ errores.nombre[0] }}
                        </div>
                    </div>
                <!-- </form> -->
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Materia.id" class="btn btn-success" @click="guardarCambios(Materia.id)">
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
    name: 'Materias',
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
            Materias: [],
            cargando: false,
            Materia: {
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
                const res = await api.get('/Logistica/Materia', {
                    params: { search: filtro }
                });
                this.Materias = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Materia = {
                id: null,
                nombre: '',
            };
            this.$refs.modalMaterias.abrir();
        },

        async agregar() {
            try {
                await api.post('/Logistica/Materia', this.Materia);
                this.consultar();
                this.$refs.modalMaterias.cerrar();

                this.Materia = {
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
            console.log(id, this.Materias);
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Materias.find(p => p.id === id);
            if (encontrado) {
                this.Materia = { ...encontrado };
                this.$refs.modalMaterias.abrir();
            }
        },

        async guardarCambios(id) {
            try {
                await api.put(`/Logistica/Materia/${id}`, this.Materia);

                this.consultar();
                this.$refs.modalMaterias.cerrar();

                this.Materia = {
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