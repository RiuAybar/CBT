<template>
  <div class="container mt-4">
    <h2 class="mb-4">Generador de Registro Escolar</h2>
    <button class="btn btn-primary mb-4" @click="generarPDF">Generar PDF</button>

    <!-- Contenedor principal para el PDF -->
    <div ref="pdfContent" class="pdf-container container">
      <!-- Primera página -->
      <div class="pdf-page">
        <!-- Encabezado -->
        <div class="header">
          <h1>GOBIERNO DEL ESTADO DE MÉXICO</h1>
          <h2>SECRETARÍA DE EDUCACIÓN</h2>
          <h2>SUBSECRETARÍA DE EDUCACIÓN MEDIA SUPERIOR Y SUPERIOR</h2>
          <div class="title-line"></div>
          <h3>REGISTRO DE FALTAS DE ASISTENCIA, CALIFICACIONES Y PROMEDIO POR ASIGNATURA</h3>
        </div>

        <!-- Primera sección con tablas -->
        <div class="form-section">
          <table class="form-table">
            <tr>
              <td class="label-cell">1/ DIRECCIÓN</td>
              <td class="wide-cell">GENERAL DE EDUCACIÓN MEDIA SUPERIOR</td>
              <td class="empty-cell">33/</td>
              <td class="empty-cell">34/</td>
            </tr>
            <tr>
              <td class="label-cell">2/ DEPARTAMENTO</td>
              <td class="wide-cell">DE BACHILLERATO TECNOLÓGICO</td>
              <td class="data-cell">MESES</td>
              <td class="data-cell">No. DE HORAS IMPARTIDAS</td>
            </tr>
            <tr>
              <td class="label-cell">3/ NOMBRE DE LA ESCUELA</td>
              <td class="wide-cell">CBT, AMANALCO DE BECERRA</td>
              <td class="data-cell">FEBRERO</td>
              <td class="data-cell">20</td>
            </tr>
            <tr>
              <td class="label-cell">4/ TURNO</td>
              <td class="wide-cell">DISCONTINUO</td>
              <td class="data-cell">MARZO</td>
              <td class="data-cell">24</td>
            </tr>
            <tr>
              <td class="label-cell">5/ NIVEL</td>
              <td class="wide-cell">MEDIO SUPERIOR TÉCNICO</td>
              <td class="data-cell">ABRIL</td>
              <td class="data-cell">15</td>
            </tr>
            <tr>
              <td class="label-cell">8/ CLAVE CENTRO DE TRABAJO</td>
              <td class="wide-cell">15ECT0112M</td>
              <td class="data-cell">MAYO</td>
              <td class="data-cell">24</td>
            </tr>
            <tr>
              <td class="label-cell">9/ No. DE C.RE.S.E.</td>
              <td class="wide-cell">NA</td>
              <td class="data-cell">JUNIO</td>
              <td class="data-cell">21</td>
            </tr>
            <tr>
              <td class="label-cell">10/ ZONA ESCOLAR</td>
              <td class="wide-cell">BT009</td>
              <td class="data-cell">JULIO</td>
              <td class="data-cell">12</td>
            </tr>
            <tr>
              <td colspan="2" class="right-align">TOTAL DE HORAS SEMESTRALES</td>
              <td colspan="2" class="data-cell">116</td>
            </tr>
          </table>

          <!-- Segunda tabla -->
          <table class="form-table">
            <tr>
              <td class="label-cell">11/ DOMICILIO</td>
              <td class="wide-cell">DOMICILIO CONOCIDO S/N</td>
              <td class="label-cell">12/ LOCALIDAD O COLONIA</td>
              <td class="wide-cell">EL POTRERO</td>
            </tr>
            <tr>
              <td class="label-cell">13/ MUNICIPIO</td>
              <td class="wide-cell">AMANALCO</td>
              <td class="label-cell">14/ TELÉFONO</td>
              <td class="wide-cell">7228353322</td>
            </tr>
            <tr>
              <td class="label-cell">15/ CICLO ESCOLAR</td>
              <td class="wide-cell">2024-2025</td>
              <td class="label-cell">16/ NOMBRE DEL PROFESOR</td>
              <td class="wide-cell">LÓPEZ SANTANA SEBASTIAN</td>
            </tr>
            <tr>
              <td class="label-cell">17/ TELÉFONO</td>
              <td class="wide-cell">7228353322</td>
              <td class="label-cell">18/ DOMICILIO</td>
              <td class="wide-cell">San Lucas, Amanalco;Méx</td>
            </tr>
            <tr>
              <td class="label-cell">19/ LOCALIDAD O COLONIA</td>
              <td class="wide-cell">San Lucas 2da secc.</td>
              <td class="label-cell">20/ ASIGNATURA</td>
              <td class="wide-cell">SUBMÓDULO II. DISEÑA Y ADMINISTRA BASES DE DATOS SIMPLES</td>
            </tr>
            <tr>
              <td class="label-cell">21/ CURSO</td>
              <td class="wide-cell">ORDINARIO</td>
              <td class="label-cell">22/ GRUPO</td>
              <td class="wide-cell">2 1</td>
            </tr>
            <tr>
              <td class="label-cell">23/ BACHILLERATO O CARRERA</td>
              <td class="wide-cell">TÉCNICO EN INFORMÁTICA</td>
              <td class="label-cell">24/ SEMESTRE</td>
              <td class="wide-cell">2</td>
            </tr>
          </table>

          <!-- Sección de horario y estadísticas -->
          <div class="bottom-section">
            <div class="schedule-section">
              <span class="section-label">36/ HORARIO</span>
              <table class="schedule-table">
                <tr>
                  <td>LUNES</td>
                  <td></td>
                  <td>JUEVES</td>
                </tr>
                <tr>
                  <td>MARTES</td>
                  <td>7:30-9:10,12:00-12:50</td>
                  <td></td>
                </tr>
                <tr>
                  <td>MIERCOLES</td>
                  <td></td>
                  <td>VIERNES</td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td>12:00-14:30</td>
                </tr>
              </table>
            </div>

            <div class="stats-section">
              <span class="section-label">35/ DATOS ESTADÍSTICOS</span>
              <table class="stats-table">
                <tr>
                  <td></td>
                  <td>HOMBRES</td>
                  <td>MUJERES</td>
                  <td>TOTAL</td>
                </tr>
                <tr>
                  <td>No. DE ALUMNOS INSCRITOS</td>
                  <td>10</td>
                  <td>13</td>
                  <td>23</td>
                </tr>
                <tr>
                  <td>BAJAS DURANTE EL AÑO</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td>EXISTENCIA AL FINAL DEL AÑO</td>
                  <td>10</td>
                  <td>13</td>
                  <td>23</td>
                </tr>
                <tr>
                  <td>No. DE APROBADOS</td>
                  <td>4</td>
                  <td>6</td>
                  <td>10</td>
                </tr>
                <tr>
                  <td>No. REPROBADOS</td>
                  <td>1</td>
                  <td>0</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td>% DE ALUMNOS APROBADOS</td>
                  <td>40.00</td>
                  <td>46.15</td>
                  <td>43.08</td>
                </tr>
                <tr>
                  <td>% DE ALUMNOS REPROBADOS</td>
                  <td>60.00</td>
                  <td>53.85</td>
                  <td>56.92</td>
                </tr>
                <tr>
                  <td>SUMA DE CALIFICACIONES</td>
                  <td>35</td>
                  <td>52</td>
                  <td>87</td>
                </tr>
                <tr>
                  <td>PROMEDIO DE CALIFICACIONES</td>
                  <td>3.50</td>
                  <td>4.00</td>
                  <td>3.78</td>
                </tr>
                <tr>
                  <td>METAS INSTITUCIONALES LOGRADAS</td>
                  <td colspan="3">NO</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

        <!-- Tabla de alumnos -->
        <table class="students-table">
          <thead>
            <tr>
              <th>No. DE LISTA</th>
              <th>SEXO</th>
              <th>NOMBRE DEL ALUMNO (A)</th>
              <th colspan="3">FALTAS DE ASISTENCIA</th>
              <th>TOT. DE FALTAS</th>
              <th>% DE INASISTENCIA</th>
              <th colspan="3">EVALUACIONES</th>
              <th>SUMA</th>
              <th>PROMEDIO</th>
              <th>OBSERVACIONES</th>
            </tr>
            <tr>
              <th colspan="3"></th>
              <th>1a</th>
              <th>2a</th>
              <th>3a</th>
              <th></th>
              <th></th>
              <th>1a</th>
              <th>2a</th>
              <th>3a</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="student in students" :key="student.id">
              <td>{{ student.id }}</td>
              <td>{{ student.gender }}</td>
              <td class="student-name">{{ student.name }}</td>
              <td>{{ student.absence1 || '' }}</td>
              <td>{{ student.absence2 || '' }}</td>
              <td>{{ student.absence3 || '' }}</td>
              <td>{{ student.totalAbsences }}</td>
              <td>{{ student.absencePercentage.toFixed(1) }}</td>
              <td>{{ student.eval1 || '' }}</td>
              <td>{{ student.eval2 || '' }}</td>
              <td>{{ student.eval3 || '' }}</td>
              <td>{{ student.totalScore }}</td>
              <td>{{ student.average.toFixed(1) }}</td>
              <td>{{ student.observations || '' }}</td>
            </tr>
          </tbody>
        </table>
        <!-- Segunda página con firmas -->
        <div>
          <div class="date-line">
              <span>El Potrero, Amanalco, México</span>
              <span class="right">jueves, 26 de junio de 2025</span>
            </div>
  
            <div class="signatures-title">FIRMAS</div>
  
            <div class="signatures-container">
              <div class="signature-box left">
                <p>LÓPEZ SANTANA SEBASTIAN</p>
                <p>DOCENTE</p>
              </div>
  
              <div class="signature-box right">
                <p>ANDREA GUADALUPE HERNÁNDEZ HERNÁNDEZ</p>
                <p>ORIENTADOR</p>
              </div>
            </div>
  
            <div class="revisor-line">REVISÓ</div>
  
            <div class="signatures-container">
              <div class="signature-box left">
                <p>MARÍA DEL CARMEN ASSENETH VELÁZQUEZ LÓPEZ</p>
                <p>SUBDIRECTORA ESCOLAR</p>
              </div>
  
              <div class="signature-box right">
                <p>GADIEL RECILLAS MIRANDA</p>
                <p>DIRECTOR ESCOLAR</p>
              </div>
            </div>
  
            <div class="validation-section">
              <div class="validation-title">VALIDACIÓN PARA CAPTURA EN SISTEMA</div>
              <div class="signature-box center">
                <p>LUIS GONZÁLEZ CALIXTO</p>
                <p>SECRETARIO ESCOLAR</p>
              </div>
            </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import html2pdf from 'html2pdf.js';

export default {
  name: 'RegistroEscolarPDF',
  data() {
    return {
      students: [
        { id: 1, gender: 'M', name: 'ARRIAGA ARRIAGA CHRISTOPHER', absence1: 0, absence2: '', absence3: '', totalAbsences: 0, absencePercentage: 0.0, eval1: 8, eval2: 8.0, eval3: 8.0, totalScore: 24, average: 8.0, observations: 'E. EXTR.' },
        { id: 2, gender: 'M', name: 'ALVAREZ COLIN LUIS ENRIQUE', absence1: 5, absence2: '', absence3: '', totalAbsences: 5, absencePercentage: 4.3, eval1: 5, eval2: 5.0, eval3: 5.0, totalScore: 15, average: 5.0, observations: '' },
        // ... completar con todos los estudiantes
      ],
    };
  },
  methods: {
    generarPDF() {
      const element = this.$refs.pdfContent
      const options = {
        margin: [5, 5, 5, 5],
        filename: 'registro_oficial_faltas.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
          scale: 3,
          letterRendering: true,
          useCORS: true,
          allowTaint: true
        },
        jsPDF: {
          unit: 'mm',
          format: 'a4',
          orientation: 'portrait',
          compressPDF: true
        },
        pagebreak: {
          mode: ['avoid-all', 'css', 'legacy'],
          before: '.page-break'
        }
      }

      html2pdf().from(element).set(options).save()
    }

  }
};
</script>

<style scoped>
/* Estilos base */
body {
  margin: 0;
  padding: 0;
  font-family: 'Arial Narrow', Arial, sans-serif;
  font-size: 7.5pt;
  line-height: 1.1;
}
.pdf-page {
  page-break-after: always;
  height: 281mm;
  overflow: hidden;
}

.page-break {
  page-break-before: always;
}

/* Encabezado */
.header {
  text-align: center;
  margin-bottom: 4mm;
}

.header h1 {
  font-size: 9.5pt;
  margin: 0.5mm 0;
  font-weight: bold;
  text-transform: uppercase;
}

.header h2 {
  font-size: 9pt;
  margin: 0.5mm 0;
  font-weight: bold;
}

.title-line {
  border-top: 1px solid black;
  margin: 1.5mm 0;
}

.header h3 {
  font-size: 9pt;
  margin: 1mm 0 2mm 0;
  font-weight: bold;
  text-decoration: underline;
}

/* Formulario */
.form-section {
  margin-bottom: 4mm;
}

.form-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.5mm;
  font-size: 7pt;
}

.form-table td {
  border: 1px solid black;
  padding: 0.8mm;
  vertical-align: middle;
}

.label-cell {
  width: 15%;
  font-weight: bold;
  white-space: nowrap;
}

.wide-cell {
  width: 35%;
}

.empty-cell {
  width: 25%;
}

.data-cell {
  width: 25%;
  text-align: center;
}

.right-align {
  text-align: right;
  padding-right: 1.5mm;
}

/* Sección inferior */
.bottom-section {
  display: flex;
  margin-top: 2mm;
}

.section-label {
  font-weight: bold;
  display: block;
  margin-bottom: 1mm;
}

.schedule-section {
  width: 40%;
  margin-right: 2mm;
}

.schedule-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 7pt;
}

.schedule-table td {
  border: 1px solid black;
  padding: 0.8mm;
  height: 5mm;
}

.stats-section {
  width: 60%;
}

.stats-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 7pt;
}

.stats-table td {
  border: 1px solid black;
  padding: 0.8mm;
  text-align: center;
}

/* Tabla de alumnos */
.students-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 6.5pt;
  margin-top: 2mm;
}

.students-table th, .students-table td {
  border: 1px solid black;
  padding: 0.7mm;
  text-align: center;
  vertical-align: middle;
}

.students-table th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.student-name {
  text-align: left;
  white-space: nowrap;
  width: 20mm;
}

/* Pie de página */
.footer-section {
  margin-top: 15mm;
}

.date-line {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12mm;
  font-size: 8pt;
}

.signatures-title {
  font-weight: bold;
  font-size: 9pt;
  margin-bottom: 15mm;
  text-decoration: underline;
  text-align: center;
}

.signatures-container {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15mm;
}

.signature-box {
  border-top: 1px solid black;
  padding-top: 1.5mm;
  margin-top: 10mm;
  width: 45%;
}

.signature-box p {
  margin: 0.8mm 0;
  font-size: 7.5pt;
  text-align: center;
}

.signature-box.left {
  margin-left: 5mm;
}

.signature-box.right {
  margin-right: 5mm;
}

.signature-box.center {
  margin: 10mm auto 0;
  width: 60%;
}

.revisor-line {
  font-weight: bold;
  margin: 5mm 0;
  text-align: center;
}

.validation-section {
  margin-top: 20mm;
}

.validation-title {
  font-weight: bold;
  margin-bottom: 5mm;
  text-align: center;
}

/* Botón de generación */
.generate-btn {
  display: block;
  margin: 20px auto;
  padding: 10px 20px;
  background-color: #0066cc;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.generate-btn:hover {
  background-color: #0052a3;
}

@media print {
  .generate-btn {
    display: none;
  }
  
  body {
    background-color: white;
  }
  
  .pdf-container {
    box-shadow: none;
    padding: 0;
    margin: 0;
    width: auto;
  }
}
</style>