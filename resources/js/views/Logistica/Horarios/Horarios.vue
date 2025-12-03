<template>
    <div>
        <div class="container-fluid p-0">
            <button v-if="Seguimiento?.id && hasPermission('puede agregar horas seguimiento')" @click="crear()"
                class="btn btn-primary float-end mt-n1 mb-1">
                <i class="bi bi-plus-circle"></i>
                Agregar Registro
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    {{ `Horas de la carrera: ${Seguimiento && Seguimiento.carrera ? Seguimiento.carrera.nombre : ''}` }}
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de Horas
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headersFiltrados" :items="SeguimientoHoras" :loading="cargando"
                            :rows-per-page="5" table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template v-if="hasPermission('puede editar horas seguimiento') || hasPermission('puede eliminar horas seguimiento') " #item-action="{ id }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary me-1" @click="editar(id)" v-if="hasPermission('puede editar horas seguimiento')">
                                        Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" @click="eliminarPermiso(id)" v-if="hasPermission('puede eliminar horas seguimiento')">
                                        Eliminar
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>
                        <div class="row">
                            <div class="col-sm-12 text-end">
                                <router-link v-if="Seguimiento?.id" to="/seguimiento" class="btn btn-danger m-2">
                                    <i class="bi bi-arrow-return-left"></i>
                                    Regresar
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal size="lg" ref="modalSeguimientoH" id="modal-SeguimientoH"
            :title="SeguimientoHora.id ? 'Editar Hora' : 'Agregar Hora'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label for="dia" class="form-label">Grupo</label>
                        <select v-model="SeguimientoHora.dia" id="dia" class="form-control">
                            <option value="LUNES" selected>LUNES</option>
                            <option value="MARTES">MARTES</option>
                            <option value="MIERCOLES">MIERCOLES</option>
                            <option value="JUEVES">JUEVES</option>
                            <option value="VIERNES">VIERNES</option>
                        </select>
                        <div v-if="errores.dia" class="form-text text-danger">
                            {{ errores.dia[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="hora_inicio" class="form-label">Grupo</label>
                        <!-- <input v-model="SeguimientoHora.hora_inicio" type="text" class="form-control" id="hora_inicio"
                            aria-describedby="Nombre"> -->
                        <div class="input-group">
                            <!-- INPUT -->
                            <input ref="horaInicioInput" v-model="SeguimientoHora.hora_inicio" type="text"
                                class="form-control" id="hora_inicio">

                            <!-- ICONO DE RELOJ -->
                            <span class="input-group-text">
                                <i class="bi bi-clock"></i>
                            </span>
                        </div>

                        <div v-if="errores.hora_inicio" class="form-text text-danger">
                            {{ errores.hora_inicio[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label for="hora_fin" class="form-label">Grupo</label>
                        <!-- <input v-model="SeguimientoHora.hora_fin" type="text" class="form-control" id="hora_fin"
                            aria-describedby="Nombre"> -->
                        <div class="input-group">
                            <!-- INPUT -->
                            <input ref="horaFinInput" v-model="SeguimientoHora.hora_fin" type="text"
                                class="form-control" id="hora_fin">

                            <!-- ICONO DE RELOJ -->
                            <span class="input-group-text">
                                <i class="bi bi-clock"></i>
                            </span>
                        </div>
                        <div v-if="errores.hora_fin" class="form-text text-danger">
                            {{ errores.hora_fin[0] }}
                        </div>
                    </div>
                </div>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="SeguimientoHora.id" class="btn btn-success" @click="guardarCambios(SeguimientoHora.id)">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Guardar Cambios
                </button>
                <button v-if="!SeguimientoHora.id && hasPermission('puede agregar grupos')" class="btn btn-success"
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

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

import debounce from 'lodash/debounce';

export default {
    name: 'Horarios',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {
            SeguimientoHoras: [],
            cargando: false,
            SeguimientoHora: {
                id: null,
                dia: null,
                hora_inicio: null,
                hora_fin: null,
            },
            busqueda: '',
            errores: {},
            Seguimiento: {},
        }
    },
    computed: {
        ...mapGetters('auth', ['hasPermission']),
        headersFiltrados() {
            const base = [
                { text: 'Id', value: 'id' },
                { text: 'Dia', value: 'dia' },
                { text: 'Hora Inicio', value: 'hora_inicio' },
                { text: 'Hora Fin', value: 'hora_fin' },

            ];
            if (this.hasPermission('puede editar horas seguimiento') || this.hasPermission('puede eliminar horas seguimiento')) {
                base.push({ text: 'Acciones', value: 'action' });
            }
            return base;
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
        initTimePickers() {
            flatpickr(this.$refs.horaInicioInput, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i:ss",
                time_24hr: true,
                minuteIncrement: 1
            });

            flatpickr(this.$refs.horaFinInput, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i:ss",
                time_24hr: true,
                minuteIncrement: 1
            });
        },
        async consultarMov() {
            try {
                const res = await api.get(`/Registro/Seguimiento/${this.$route.params.id}`);
                this.Seguimiento = res.data;
            } catch (error) {
                if (error.response && error.response.status === 404) {
                    this.$swal.fire('Error', '❌ El seguimiento no existe', 'error');
                    this.$router.replace({ name: 'seguimiento' }); // Redirige a la lista de roles
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
                const res = await api.get(`/Logistica/seguimientoHorarios/${this.$route.params.id}/index`, {
                    params: { search: filtro }
                });
                this.SeguimientoHoras = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.SeguimientoHora = {
                id: null,
                dia: null,
                hora_inicio: null,
                hora_fin: null,
            };
            this.$refs.modalSeguimientoH.abrir();
            this.$nextTick(() => {
                this.initTimePickers();
            });
        },

        async agregar() {
            try {
                this.SeguimientoHora.seguimiento_id = this.$route.params.id;
                await api.post(`/Logistica/SeguimientoHorario`, this.SeguimientoHora);
                this.consultar();
                this.$refs.modalSeguimientoH.cerrar();

                this.SeguimientoHora = {
                    id: null,
                    dia: null,
                    hora_inicio: null,
                    hora_fin: null,
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
            const encontrado = this.SeguimientoHoras.find(p => p.id === id);
            if (encontrado) {
                this.SeguimientoHora = { ...encontrado };
                this.$refs.modalSeguimientoH.abrir();
                this.$nextTick(() => {
                    this.initTimePickers();
                });
            }
        },

        async guardarCambios(id) {
            try {
                this.SeguimientoHora.seguimiento_id = this.$route.params.id;
                await api.put(`/Logistica/SeguimientoHorario/${id}`, this.SeguimientoHora);
                this.consultar();
                this.$refs.modalSeguimientoH.cerrar();

                this.SeguimientoHora = {
                    id: null,
                    dia: null,
                    hora_inicio: null,
                    hora_fin: null,
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
        eliminarPermiso(id) {
            this.$swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el registro?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // 🚨 Petición DELETE a API
                    try {
                        await api.delete(`/Logistica/SeguimientoHorario/${id}`, {
                            data: {
                                Permiso: id
                            }
                        });
                        this.consultar();
                        this.$swal.fire('Éxito', `✅ Registro eliminado correctamente`, 'success');
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
        this.consultar();
        this.consultarMov();
    }
}
</script>

<style></style>