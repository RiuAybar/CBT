<template>
    <div class="container mt-5">
      <h2>Lista de Usuarios</h2>
  
      <input v-model="busqueda" placeholder="Buscar en servidor..." class="form-control mb-3" />
  

      <EasyDataTable
        :headers="headers"
        :items="usuarios"
        :loading="cargando"
        :rows-per-page="5"
        table-class="table table-bordered"
      />
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import EasyDataTable from 'vue3-easy-data-table';
  import 'vue3-easy-data-table/dist/style.css';
  
  export default {
    name: 'Users',
    components: {
      EasyDataTable
    },
    data() {
      return {
        usuarios: [],
        cargando: false,
        busqueda: '',
        headers: [
          { text: 'ID', value: 'id' },
          { text: 'Nombre', value: 'name' },
          { text: 'Email', value: 'email' },
          { text: 'Fecha de Registro', value: 'created_at' }
        ]
      };
    },
    watch: {
      // 👀 Observa cada cambio en la búsqueda
      busqueda: {
        handler(val) {
          this.consultarUsuarios(val);
        },
        immediate: true
      }
    },
    methods: {
      async consultarUsuarios(filtro = '') {
        this.cargando = true;
        try {
          const res = await axios.get('/api/users', {
            params: { search: filtro }
          });
          this.usuarios = res.data;
        } catch (error) {
          console.error('Error al consultar usuarios:', error);
        } finally {
          this.cargando = false;
        }
      }
    },
    mounted() {
      this.consultarUsuarios();
    }
  };
  </script>
  