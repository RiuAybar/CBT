<template>
  <div class="container">
    <div class="container mt-4">
      <h2>Contador: {{ contador }}</h2>
      <button @click="incrementar" class="btn btn-primary me-2">Incrementar</button>
      <button @click="reiniciar" class="btn btn-secondary">Reiniciar</button>
      <p>Doble del contador: {{ doble }}</p>
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