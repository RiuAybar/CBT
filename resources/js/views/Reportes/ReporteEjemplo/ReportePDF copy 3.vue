<template>
  <div class="container mt-4">
    <h2 class="mb-4">Generador de Registro Escolar</h2>
    <button class="btn btn-primary mb-4" @click="generarPDF">Generar PDF</button>

    <!-- Contenido oculto para el PDF -->
    <div class="container" ref="pdfContent"
      style="visibility: hidden; position: absolute; left: -9999px; width: 210mm;">
      <div class="reporte-container">
        <!-- Encabezado institucional -->
        <div class="text-center mb-3">
          <h5 class="mb-0">GOBIERNO DEL ESTADO DE MÉXICO</h5>
          <h5 class="mb-0">SECRETARÍA DE EDUCACIÓN</h5>
          <h5 class="mb-0">SUBSECRETARÍA DE EDUCACIÓN MEDIA SUPERIOR Y SUPERIOR</h5>
          <h4 class="mt-3"><strong>REGISTRO DE FALTAS DE ASISTENCIA, CALIFICACIONES Y PROMEDIO POR ASIGNATURA</strong>
          </h4>
        </div>

        <!-- Información general -->
        <table class="table table-bordered table-sm mb-3">
          <tbody>
            <tr>
              <td><strong>GENERAL DE EDUCACIÓN MEDIA SUPERIOR</strong></td>
              <td><strong>MESES</strong></td>
              <td><strong>No. DE HORAS IMPARTIDAS</strong></td>
            </tr>
            <tr>
              <td rowspan="6"><strong>DE BACHILLERATO TECNOLÓGICO</strong><br><strong>CBT, AMANALCO DE BECERRA</strong>
              </td>
              <td>FEBRERO</td>
              <td>20</td>
            </tr>
            <tr>
              <td>MARZO</td>
              <td>24</td>
            </tr>
            <tr>
              <td>ABRIL</td>
              <td>15</td>
            </tr>
            <tr>
              <td>MAYO</td>
              <td>24</td>
            </tr>
            <tr>
              <td>JUNIO</td>
              <td>21</td>
            </tr>
            <tr>
              <td>JULIO</td>
              <td>12</td>
            </tr>
            <tr>
              <td colspan="2"><strong>TOTAL DE HORAS SEMESTRALES</strong></td>
              <td><strong>116</strong></td>
            </tr>
          </tbody>
        </table>

        <!-- Datos del plantel -->
        <table class="table table-bordered table-sm mb-3">
          <tbody>
            <tr>
              <td><strong>DOMICILIO CONOCIDO S/N</strong></td>
              <td><strong>EL POTRERO</strong></td>
              <td><strong>No. DE ALUMNOS INSCRITOS</strong></td>
              <td>10</td>
              <td>13</td>
              <td>23</td>
            </tr>
            <tr>
              <td><strong>AMANALCO</strong></td>
              <td><strong>7228353322</strong></td>
              <td><strong>BAJAS DURANTE EL AÑO</strong></td>
              <td>0</td>
              <td>0</td>
              <td>0</td>
            </tr>
            <tr>
              <td><strong>LÓPEZ SANTANA SEBASTIAN</strong></td>
              <td><strong>7228353322</strong></td>
              <td><strong>EXISTENCIA AL FINAL DEL AÑO</strong></td>
              <td>10</td>
              <td>13</td>
              <td>23</td>
            </tr>
            <tr>
              <td><strong>SAN LUCAS, ArmandoOMEx</strong></td>
              <td><strong>San Lucas 2da secc.</strong></td>
              <td><strong>No. DE APROBADOS</strong></td>
              <td>4</td>
              <td>6</td>
              <td>10</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><strong>No. REPROBADOS</strong></td>
              <td>1</td>
              <td>0</td>
              <td>1</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><strong>% DE ALUMNOS APROBADOS</strong></td>
              <td>40.00</td>
              <td>46.15</td>
              <td>43.08</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><strong>% DE ALUMNOS REPROBADOS</strong></td>
              <td>60.00</td>
              <td>53.85</td>
              <td>56.92</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><strong>SUMA DE CALIFICACIONES</strong></td>
              <td>35</td>
              <td>52</td>
              <td>87</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><strong>PROMEDIO DE CALIFICACIONES</strong></td>
              <td>3.50</td>
              <td>4.00</td>
              <td>3.78</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td><strong>METAS INSTITUCIONALES LOGRADAS</strong></td>
              <td colspan="3">NO</td>
            </tr>
          </tbody>
        </table>

        <!-- Tabla de alumnos -->
        <h6 class="text-center mb-2"><strong>SUBMÓDULO II: DISEÑA Y ADMINISTRA BASES DE DATOS SIMPLES</strong></h6>
        <table class="table table-bordered table-sm mb-4">
          <thead>
            <tr>
              <th rowspan="2">No. DE LISTA</th>
              <th rowspan="2">NOMBRE DEL ALUMNO (A)</th>
              <th colspan="3">FALTAS DE ASISTENCIA</th>
              <th rowspan="2">TOT. DE FALTAS</th>
              <th rowspan="2">% DE INASISTENCIA</th>
              <th colspan="3">EVALUACIONES</th>
              <th rowspan="2">SUMA</th>
              <th rowspan="2">PROMEDIO</th>
              <th rowspan="2">OBSERVACIONES</th>
            </tr>
            <tr>
              <th>1a</th>
              <th>2a</th>
              <th>3a</th>
              <th>1a</th>
              <th>2a</th>
              <th>3a</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(alumno, index) in alumnos" :key="index">
              <td>{{ alumno.numero }}</td>
              <td>{{ alumno.nombre }}</td>
              <td>{{ alumno.faltas[0] }}</td>
              <td>{{ alumno.faltas[1] }}</td>
              <td>{{ alumno.faltas[2] }}</td>
              <td>{{ alumno.totalFaltas }}</td>
              <td>{{ alumno.porcentajeInasistencia }}</td>
              <td>{{ alumno.evaluaciones[0] }}</td>
              <td>{{ alumno.evaluaciones[1] }}</td>
              <td>{{ alumno.evaluaciones[2] }}</td>
              <td>{{ alumno.suma }}</td>
              <td>{{ alumno.promedio }}</td>
              <td>{{ alumno.observaciones }}</td>
            </tr>
          </tbody>
        </table>

        <!-- Firmas -->
        <div class="firmas-container mt-5">
          <div class="row">
            <div class="col text-center">
              <p>El Potrero, Amanalco, México {{ fechaActual }}</p>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col text-center">
              <p class="mb-0"><strong>LÓPEZ SANTANA SEBASTIAN</strong></p>
              <p class="mt-0">DOCENTE</p>
            </div>
            <div class="col text-center">
              <p class="mb-0"><strong>REVISÓ</strong></p>
              <p class="mb-0"><strong>AMOREA GUADALUPE HERNÁNDEZ HERNÁNDEZ</strong></p>
              <p class="mt-0">ORIENTADOR</p>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col text-center">
              <p class="mb-0"><strong>AUTORIZÓ</strong></p>
              <p class="mb-0"><strong>MAÑA DEL CARMEN ASSENETH VELÁZQUEZ LÓPEZ</strong></p>
              <p class="mt-0">SUBDIRECTORA ESCOLAR</p>
            </div>
            <div class="col text-center">
              <p class="mb-0"><strong>GADIEL RECILLAS MIRANDA</strong></p>
              <p class="mt-0">DIRECTOR ESCOLAR</p>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col text-center">
              <p class="mb-0"><strong>VALIDACIÓN PARA CAPTURA EN SISTEMA</strong></p>
              <p class="mb-0"><strong>LUIS GONZÁLEZ CALIXTO</strong></p>
              <p class="mt-0">SECRETARIO ESCOLAR</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <iframe v-if="pdfUrl" :src="pdfUrl" width="100%" height="800px"></iframe>

  </div>
</template>

<script>
import html2pdf from 'html2pdf.js';

export default {
  name: 'RegistroEscolarPDF',
  data() {
    return {
      fechaActual: new Date().toLocaleDateString('es-MX', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      }),
      alumnos: [
        { numero: 1, nombre: 'ARRIAGA ARRIAGA CHRISTOPHER', faltas: [0, '', ''], totalFaltas: 0, porcentajeInasistencia: '0.0', evaluaciones: [8, '8.0', '8.0'], suma: 8, promedio: 8.0, observaciones: 'E. EXTR.' },
        { numero: 2, nombre: 'ALVAREZ COLIN LUIS ENRIQUE', faltas: [5, '', ''], totalFaltas: 5, porcentajeInasistencia: '4.3', evaluaciones: [5, '5.0', '5.0'], suma: 5, promedio: 5.0, observaciones: '' },
        // ... agregar más alumnos según sea necesario
      ],
      pdfUrl: null,
    };
  },
  methods: {
    // generarPDF() {
    //   const element = this.$refs.pdfContent;

    //   // Mostrar temporalmente el contenido
    //   element.style.visibility = 'visible';
    //   element.style.position = 'static';
    //   element.style.left = '0';

    //   const options = {
    //     margin: 0.5,
    //     filename: 'registro_escolar.pdf',
    //     image: { type: 'jpeg', quality: 0.98 },
    //     html2canvas: { 
    //       scale: 2,
    //       letterRendering: true,
    //       useCORS: true
    //     },
    //     jsPDF: { 
    //       unit: 'mm', 
    //       format: 'a4', 
    //       orientation: 'portrait' 
    //     }
    //   };

    //   html2pdf()
    //     .set(options)
    //     .from(element)
    //     .save()
    //     .then(() => {
    //       // Ocultar nuevamente el contenido
    //       element.style.visibility = 'hidden';
    //       element.style.position = 'absolute';
    //       element.style.left = '-9999px';
    //     });
    // }
    generarPDF() {
      const element = this.$refs.pdfContent;

      // Mostrar temporalmente el contenido
      element.style.visibility = 'visible';
      element.style.position = 'static';
      element.style.left = '0';

      const options = {
        margin: 0.5,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };

      html2pdf()
        .set(options)
        .from(element)
        .outputPdf('blob')
        .then((pdfBlob) => {
          const pdfUrl = URL.createObjectURL(pdfBlob);
          window.open(pdfUrl, '_blank'); // Abre una nueva pestaña con la vista previa

          // Restaurar estilo original
          element.style.visibility = 'hidden';
          element.style.position = 'absolute';
          element.style.left = '-9999px';
        });

      // html2pdf()
      //   .set(options)
      //   .from(element)
      //   .outputPdf('blob')
      //   .then((pdfBlob) => {
      //     this.pdfUrl = URL.createObjectURL(pdfBlob);
      //     // Restaurar estilo oculto
      //     element.style.visibility = 'hidden';
      //     element.style.position = 'absolute';
      //     element.style.left = '-9999px';
      //   });

    }

  }
};
</script>

<style scoped>
.reporte-container {
  font-family: Arial, sans-serif;
  font-size: 10pt;
  line-height: 1.2;
}

.table {
  width: 100%;
  border-collapse: collapse;
  font-size: 9pt;
}

.table th,
.table td {
  border: 1px solid #000;
  padding: 3px;
  text-align: center;
}

.table th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.text-center {
  text-align: center;
}

.firmas-container {
  margin-top: 30px;
}

.firmas-container p {
  margin-bottom: 5px;
}

.mt-0 {
  margin-top: 0;
}

.mb-0 {
  margin-bottom: 0;
}
</style>