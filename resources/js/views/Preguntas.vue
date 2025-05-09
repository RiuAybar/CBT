<template>
    <div class="container mt-5">
        <h2>Lista de preguntas</h2>
        <div class="row">
            <div class="col-sm-6">
                <button @click="mostrarModal" class="btn btn-success">
                    <i class="bi bi-plus-square"></i>
                    Agregar Preguntas
                </button>
            </div>
            <div class="col-sm-6">
                <input v-model="busqueda" placeholder="Buscar" class="form-control mb-3">
            </div>

            <EasyDataTable :headers="headers" :items="preguntas" :loading="cargando" :rows-per-page="5"
                :text="textosTabla" 
                border-cell
                buttons-pagination
                
                >
                <template #item-action="item">
                    <div class="btn-group">
                        <button @click="editarPreguta(item.id)" class="btn btn-sm btn-info me-1">Editar</button>
                        <button @click="eliminrPregunta(item.id)" class="btn btn-sm btn-danger ">Eliminar</button>
                    </div>
                </template>
            </EasyDataTable>

            <Modal ref="modalComponente" id="modal-componente" title="Detalles de la pregunta"
                :productoId="pregunta.id">
                <template #default>
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Titulo</label>
                        <input v-model="pregunta.titulo" type="text" class="form-control" id="titulo"
                            placeholder="Tutulo">
                        <div v-if="errores.titulo" class="form-text text-danger">
                            {{ errores.titulo[0] }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <textarea v-model="pregunta.descripcion" class="form-control" id="descripcion"
                            rows="3"></textarea>
                        <div v-if="errores.descripcion" class="form-text text-danger">
                            {{ errores.descripcion[0] }}
                        </div>
                    </div>
                </template>
                <template #footer>
                    <button v-if="pregunta.id" @click="actualizarPregunta(pregunta.id)"
                        class="btn btn-success">Actualizar</button>
                    <button v-else @click="agregarPregunta()" class="btn btn-success">Agregar</button>
                </template>
            </Modal>

        </div>
    </div>
</template>
<script>
import api from '../services/api';
import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';
import Modal from '../components/Modal.vue';

export default {
    name: 'Preguntas',
    components: {
        EasyDataTable,
        Modal,
    },
    data() {
        return {
            preguntas: [],
            busqueda: '',
            headers: [
                { text: 'Titulo', value: 'titulo', sortable: true },
                { text: 'Descripcion', value: 'descripcion', sortable: true },
                { text: 'Acciones', value: 'action', sortable: false },
            ],
            cargando: false,
            pregunta: {
                id: null,
                titulo: '',
                descripcion: '',
            },
            errores: {},
            textosTabla: {
                rowsPerPageText: 'Filas por página:',
                pageText: 'Página {0} de {1}', // Aquí cambiamos "of" por "de"
                noDataText: 'No hay datos disponibles',
            },
        }
    },
    watch: {
        busqueda: {
            handler(val) {
                this.obtenerPreguntas(val);
            },
            immediate: true
        }
    },
    methods: {
        async obtenerPreguntas(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get('preguntas', {
                    params: { search: filtro }
                });
                this.preguntas = res.data;
            } catch (error) {

            } finally {
                this.cargando = false;
            }
        },
        mostrarModal() {
            this.pregunta = {
                id: null,
                titulo: '',
                descripcion: '',
            };
            this.errores = {};
            this.$refs.modalComponente.abrir();
        },
        async agregarPregunta() {
            try {
                await api.post('/preguntas', this.pregunta);
                this.pregunta = {
                    id: null,
                    titulo: '',
                    descripcion: '',
                };
                this.obtenerPreguntas();
                this.$refs.modalComponente.cerrar();
                this.$swal.fire('exito', 'Pregunta agregada correctamente', 'success');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', 'No se pudo agregar la pregunta', 'error');
                }
            }
        },
        editarPreguta(id) {
            const encontrado = this.preguntas.find(p => p.id === id);
            if (encontrado) {
                this.pregunta = { ...encontrado };
            }
            console.log(encontrado);
            this.$refs.modalComponente.abrir();
        },
        async actualizarPregunta(id) {
            try {
                await api.put(`/preguntas/${id}`, this.pregunta);
                this.pregunta = {
                    id: null,
                    titulo: '',
                    descripcion: '',
                };
                this.obtenerPreguntas();
                this.$refs.modalComponente.cerrar();
                this.errores = {};
                this.$swal.fire('exito', 'Pregunta agregada correctamente', 'success');

            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', 'No se pudo agregar la pregunta', 'error');
                }
            }
        },
        async eliminrPregunta(id) {
            try {
                await api.delete(`/preguntas/${id}`);
                this.obtenerPreguntas();
                this.$swal.fire('exito', 'Se elimino correctamente el registro.', 'success');
            } catch (error) {
                console.error(error);
                this.swal.fire('Error', `Consulte con el administrador`, 'error');
            }
        }
    },
    mounted() {
        this.obtenerPreguntas();
    }

}

</script>

<style></style>