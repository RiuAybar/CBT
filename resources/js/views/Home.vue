<template>
  <div class="container">
    <div class="mb-3">
      <h1 class="h3 d-inline align-middle">¡Bienvenido(a)!</h1>
      <b class="badge bg-primary ms-2" href="https://adminkit.io/pricing/" target="_blank">
        Instituto CBT 
        <i class="bi bi-person-workspace"></i>
      </b>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header px-4 pt-4">
            <h5 class="card-title mb-0">Bienvenido al Sistema Escolar.</h5>
          </div>
          <div class="card-body px-6 pt-2">
            <p>
              Nos complace darte la bienvenida.
              Aquí podrás gestionar tus calificaciones académicas de forma fácil y eficiente.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useStore } from 'vuex';
import { computed } from 'vue';

export default {
  name: 'Home',
  components: {
  },
  setup() {
    const store = useStore();

    const contador = computed(() => store.state.contador);
    const doble = computed(() => store.getters.dobleContador);

    const incrementar = () => store.commit('incrementar');
    const reiniciar = () => store.commit('reiniciar');

    return { contador, incrementar, reiniciar, doble };
  }
}
</script>