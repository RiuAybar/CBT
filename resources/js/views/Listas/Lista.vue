<template>
  <main class="content">
    <div class="container-fluid p-0">
      <button @click="crear" class="btn btn-primary float-end mt-n1">
        <i class="align-middle me-2" data-feather="plus-circle"></i>
        Agregar
      </button>
      <h1 class="h3 mb-3"><strong>Lista</strong></h1>
      <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <h5 class="card-title mb-0">Lista de asistencia</h5>
                </div>
                <div class="col-sm-6">
                  <input v-model="busqueda" placeholder="Buscar en servidor..." class="form-control mb-3" />
                </div>
              </div>
            </div>

            <div v-if="cargando" class="text-center p-3">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Cargando...
            </div>

            <EasyDataTable v-if="columns.length && rows.length && !cargando" :columns="columns" :items="rows"
              :stripe="true" :pagination="true" :rows-per-page="10" :pagination-align="'right'" />

            <div v-else-if="!cargando" class="text-center p-3">
              No hay datos para mostrar
            </div>

          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import api from '../../services/api';
import debounce from 'lodash.debounce';
import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

export default {
  name: 'Lista',
  components: { EasyDataTable },
  data() {
    return {
      columns: [],
      rows: [],
      busqueda: '',
      cargando: false,
    };
  },
  watch: {
    busqueda: {
      handler: debounce(function (val) {
        this.consultar(val);
      }, 300),
      immediate: true,
    },
  },
  methods: {
    async consultar(filtro = '') {
      this.cargando = true;
      try {
        const response = await api.get('/Estudiuante/Lista', {
          params: { search: filtro },
        });
        this.columns = response.data.columns.map((col) => ({
          label: col.toUpperCase(),
          field: col,
        }));
        this.rows = response.data.data;
      } catch (error) {
        console.error('Error al consultar:', error);
      } finally {
        this.cargando = false;
      }
    },
    crear() {
      alert('Funcionalidad Agregar pendiente');
    },
  },
  mounted() {
    this.consultar();
  },
};
</script>
