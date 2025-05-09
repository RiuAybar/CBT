<template>
    <div class="container mt-5">
        <h2>Lista de Productos</h2>

        <div class="row">
            <div class="col-sm-6">
                <button @click="crearProducto()" class="btn btn-success"> <i class="bi bi-plus-square"></i> Agregar
                    producto</button>
            </div>
            <div class="col-sm-6">
                <input v-model="busqueda" placeholder="Buscar en servidor..." class="form-control mb-3" />
            </div>
        </div>


        <EasyDataTable :headers="headers" :items="Productos" :loading="cargando" :rows-per-page="5"
            table-class="table table-bordered">

            <!-- 🎯 Columna de acciones personalizada -->
            <template #item-action="{ id, nombre, descripcion, precio, stock }">
                <div class="btn-group">
                    <button class="btn btn-sm btn-warning me-1" @click="editarProducto(id)">Editar</button>
                    <button class="btn btn-sm btn-danger" @click="eliminarProducto(id, nombre)">Eliminar</button>
                </div>
            </template>
        </EasyDataTable>

        <Modal ref="modalProducto" id="modal-producto" title="Detalles del producto" :productoId="producto.id">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <form>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input v-model="producto.nombre" type="text" class="form-control" id="nombre"
                            aria-describedby="Nombre">
                        <div v-if="errores.nombre" class="form-text text-danger">
                            {{ errores.nombre[0] }}
                        </div>
                    </div>
                    <div class="form-floating">
                        <textarea v-model="producto.descripcion" class="form-control" placeholder="Descripcion"
                            id="descripcion"></textarea>
                        <label for="descripcion">Descripcion</label>
                        <div v-if="errores.descripcion" class="form-text text-danger">
                            {{ errores.descripcion[0] }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input v-model="producto.precio" type="number" class="form-control" id="precio">
                        <div v-if="errores.precio" class="form-text text-danger">
                            {{ errores.precio[0] }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input v-model="producto.stock" type="number" class="form-control" id="stock">
                        <div v-if="errores.stock" class="form-text text-danger">
                            {{ errores.stock[0] }}
                        </div>
                    </div>
                </form>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="producto.id" class="btn btn-success" @click="guardarCambios(producto.id)"> Guardar Cambios
                </button>
                <button v-else class="btn btn-success" @click="agregarProducto()"> Agregar </button>
            </template>
        </Modal>
    </div>
</template>

<script>
import Modal from '../components/Modal.vue';
import api from '../services/api';

import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

export default {
    name: 'Productos',
    components: {
        EasyDataTable,
        Modal
    },
    data() {
        return {

            producto: {
                id: null,
                descripcion: '',
                precio: 0,
                stock: 0
            },
            Productos: [],
            cargando: false,
            busqueda: '',
            headers: [
                { text: 'Nombre', value: 'nombre' },
                { text: 'Descripcion', value: 'descripcion' },
                { text: 'Precio', value: 'precio' },
                { text: 'Stock', value: 'stock' },
                { text: 'Acciones', value: 'action' },
            ],
            errores: {},
        };
    },
    watch: {
        // 👀 Observa cada cambio en la búsqueda
        busqueda: {
            handler(val) {
                this.consultarProductos(val);
            },
            immediate: true
        }
    },
    methods: {
        async consultarProductos(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get('/productos', {
                    params: { search: filtro }
                });
                this.Productos = res.data;
            } catch (error) {
                console.error('Error al consultar Productos:', error);
            } finally {
                this.cargando = false;
            }
        },
        crearProducto() {
            this.errores = {}; // 🔄 Limpia los errores
            this.producto = {
                id: null,
                descripcion: '',
                precio: 0,
                stock: 0
            };
            this.$refs.modalProducto.abrir();
        },
        editarProducto(id) {
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Productos.find(p => p.id === id);
            if (encontrado) {
                this.producto = { ...encontrado };
                this.$refs.modalProducto.abrir();
            }
        },
        eliminarProducto(id, nombre) {
            this.$swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar el producto ${nombre}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    // 🚨 Aquí podrías hacer una petición DELETE a tu API como:
                    try {
                        await api.delete(`/productos/${id}`);
                        this.consultarProductos();
                        this.$refs.modalProducto.cerrar();

                        this.producto = {
                            id: null,
                            nombre: '',
                            descripcion: '',
                            precio: 0,
                            stock: 0
                        };
                        this.$swal.fire('Éxito', `✅ Producto ${nombre} eliminado correctamente`, 'success');
                        this.errores = {}; // 🔄 Limpia los errores
                    } catch (error) {
                        if (error.response && error.response.status === 422) {
                            this.errores = error.response.data.errors;
                        } else {
                            console.error(error);
                            this.$swal.fire('Error', '❌ No se pudo agregar el producto', 'error');
                        }
                    }
                }
            });
        },
        async agregarProducto() {
            try {
                await api.post('/productos', this.producto);
                this.consultarProductos();
                this.$refs.modalProducto.cerrar();

                this.producto = {
                    id: null,
                    nombre: '',
                    descripcion: '',
                    precio: 0,
                    stock: 0
                };
                this.errores = {}; // 🔄 Limpia los errores
                this.$swal.fire('Éxito', '✅ Producto agregado correctamente', 'success');
            } catch (error) {

                if (error.response && error.response.status === 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', '❌ No se pudo agregar el producto', 'error');
                }

            }
        },
        async guardarCambios(id) {
            try {
                await api.put(`/productos/${id}`, this.producto);

                this.consultarProductos();
                this.$refs.modalProducto.cerrar();

                this.producto = {
                    id: null,
                    nombre: '',
                    descripcion: '',
                    precio: 0,
                    stock: 0
                };
                this.$swal.fire('Éxito', '✅ Producto actualizado correctamente', 'success');
                this.errores = {}; // 🔄 Limpia los errores
            } catch (error) {

                if (error.response && error.response.status === 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', '❌ No se pudo agregar el producto', 'error');
                }

            }
        }
    },
    mounted() {
        this.consultarProductos();
    }
};
</script>