<template>
  <div class="container mt-5">
    <h2 class="mb-4">Generador de PDF con Vue 3 + Bootstrap</h2>
    <button class="btn btn-primary mb-3" @click="verPDF">Ver PDF</button>

    <!-- Contenido oculto que se renderiza para capturar -->
    <div ref="reporteRef" style="visibility: hidden; position: absolute; left: -9999px;">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Reporte de ejemplo</h4>
        </div>
        <div class="card-body">
          <p>Este es un ejemplo de cómo generar un PDF con contenido HTML usando Vue 3, Bootstrap y html2pdf.js</p>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(usuario, index) in usuarios" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ usuario.nombre }}</td>
                <td>{{ usuario.correo }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import html2pdf from 'html2pdf.js';

export default {
  name: 'ReportePDF',
  data() {
    return {
      usuarios: [
        { nombre: 'Juan Pérez', correo: 'juan@example.com' },
        { nombre: 'Ana Gómez', correo: 'ana@example.com' },
        { nombre: 'Carlos López', correo: 'carlos@example.com' },
      ]
    };
  },
  methods: {
    verPDF() {
      const element = this.$refs.reporteRef;

      // Mostrar temporalmente el contenido oculto
      element.style.visibility = 'visible';
      element.style.position = 'static';
      element.style.left = '0';

      const options = {
        margin:       0.5,
        filename:     'reporte.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
      };

      html2pdf()
        .set(options)
        .from(element)
        .outputPdf('bloburl')
        .then(pdfUrl => {
          // Ocultar nuevamente el contenido
          element.style.visibility = 'hidden';
          element.style.position = 'absolute';
          element.style.left = '-9999px';

          // Abrir el PDF en una nueva pestaña
          window.open(pdfUrl, '_blank');
        });
    }
  }
};
</script>
