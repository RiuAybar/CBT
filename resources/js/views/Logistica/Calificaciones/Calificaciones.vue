<template>
  <div>
    <div class="container-fluid p-0">
      <h1 class="h3 mb-3">
        <strong>
          Calificaciones
        </strong>
      </h1>
      <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
          <div class="card flex-fill">
            <div class="card-header">
              <div class="row">
                <div class="col-sm-6">
                  <h5 class="card-title mb-0">
                    Mis calificaciones
                  </h5>
                </div>
                <div class="col-sm-6">
                  <input v-model="busqueda" placeholder="Buscar en servidor..." class="form-control mb-3" />
                </div>
              </div>
            </div>
            <EasyDataTable :headers="headers" :items="Calificaciones" :loading="cargando" :rows-per-page="5"
              table-class="table table-hover my-0">
            </EasyDataTable>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import api from '../../../services/api';
import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';
import debounce from 'lodash/debounce';


export default {
  name: 'Users',
  components: {
    EasyDataTable,
  },
  data() {
    return {
      headers: [],
      cargando: false,
      busqueda: '',
      Calificaciones: [],
      errores: {},
    }
  },
  computed: {
    ...mapGetters(['selectedYear']),
    ...mapGetters('auth', ['hasPermission'])
  },
  watch: {
    // 👀 Observa cada cambio en la búsqueda
    busqueda: {
      handler: debounce(function (val) {
        this.consultar(val);
      }, 300),
      immediate: true
    },
    selectedYear(newYear, oldYear) {
      this.consultar(this.busqueda);
    }
  },
  methods: {
    async consultar(filtro = '') {
      this.cargando = true;
      try {
        const res = await api.get(`/Estudiuante/Lista/VistaCalificaciones`, {
          params: {
            search: filtro,
            ano: this.selectedYear
          }
        });
        // === Datos de materias ===
        this.Calificaciones = res.data.materias;
        // === Parciales dinámicos ===
        const parciales = res.data.parciales; // ["Parcial 1","Parcial 2",...]
        // === Construir headers dinámicos ===
        this.headers = [
          { text: "Materia", value: "materia" },
          ...parciales.map(p => ({ text: p, value: p })),
          { text: "Promedio", value: "promedio" },
          { text: "T.E.", value: "T_E" }
        ];
      } catch (error) {
        console.error('Error al consultar:', error);
      } finally {
        this.cargando = false;
      }
    }
  },
  mounted() {
    this.consultar();
  }
}
</script>

<style></style>