<template>
  <div class="container mt-4">
    <h2 class="mb-4">Generador de Registro Escolar</h2>
    <button class="btn btn-primary mb-4" @click="generarPDF" :disabled="loading">
      <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      {{ loading ? 'Generando...' : 'Generar PDF' }}
    </button>
    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
    <!-- Contenedor principal para el PDF -->
    <div ref="pdfContent" class="pdf-container container">

      <div class="m-3">
        <table>
          <thead>
            <tr>
              <th>
                <img :src="logo" width="120" height="30">
              </th>
              <th class="px-2">
                <h4 class="mb-0">GOBIERNO DEL ESTADO DE MÉXICO</h4>
                <h5 class="mb-0">SECRETARÍA DE EDUCACIÓN</h5>
                <h5 class="mb-0">SUBSECRETARÍA DE EDUCACIÓN MEDIA SUPERIOR Y SUPERIOR</h5>
              </th>
            </tr>
          </thead>
        </table>
      </div>


      <table style="border: 2px solid black;">

        <tr>
          <td colspan="2" class="text-center bold bg-header" style="font-size: 10px; border-bottom: 2px solid black;">
            REGISTRO DE FALTAS DE ASISTENCIA, CALIFICACIONES Y PROMEDIO POR ASIGNATURA
          </td>
        </tr>

        <tr>
          <td class="nested-wrapper" style="width: 65%;">
            <table style="width: 100%; height: 100%;">
              <tr>
                <td colspan="4">
                  <span class="label-tiny">1/ DIRECCIÓN</span>
                  <div class="text-center bold">GENERAL DE EDUCACIÓN MEDIA SUPERIOR</div>
                </td>
              </tr>
              <tr>
                <td colspan="4">
                  <span class="label-tiny">2/ DEPARTAMENTO</span>
                  <div class="text-center">DE BACHILLERATO TECNOLÓGICO</div>
                </td>
              </tr>
              <tr>
                <td colspan="4">
                  <span class="label-tiny">3/ NOMBRE DE LA ESCUELA</span>
                  <div class="text-center bold" style="padding: 5px;">CBT, AMANALCO DE BECERRA</div>
                </td>
              </tr>
              <tr>
                <td style="width: 25%;">
                  <span class="label-tiny">4/ TURNO</span>
                  <div class="text-center bold">DISCONTINUO</div>
                </td>
                <td style="width: 25%;">
                  <span class="label-tiny">5/ NIVEL</span>
                  <div class="text-center bold">MEDIO SUPERIOR TÉCNICO</div>
                </td>
                <td style="width: 50%;" colspan="2"></td>
              </tr>
              <tr>
                <td>
                  <span class="label-tiny">8/ CLAVE CENTRO DE TRABAJO</span>
                  <div class="text-center bold">15ECT0112M</div>
                </td>
                <td>
                  <span class="label-tiny">9/ No. DE R.E.V.O.E.</span>
                  <div class="text-center">NA</div>
                </td>
                <td colspan="2">
                  <span class="label-tiny">10/ ZONA ESCOLAR</span>
                  <div class="text-center">BT009</div>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <span class="label-tiny">11/ DOMICILIO</span>
                  <div class="bold">DOMICILIO CONOCIDO S/N</div>
                </td>
                <td colspan="2">
                  <span class="label-tiny">12/ LOCALIDAD O COLONIA</span>
                  <div class="bold">EL POTRERO</div>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="label-tiny">13/ MUNICIPIO</span>
                  <div class="bold text-center">AMANALCO</div>
                </td>
                <td>
                  <span class="label-tiny">14/ TELÉFONO</span>
                  <div class="bold text-center">7228353322</div>
                </td>
                <td colspan="2">
                  <span class="label-tiny">15/ CICLO ESCOLAR</span>
                  <div class="bold text-center">2024-2025</div>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <span class="label-tiny">16/ NOMBRE DEL PROFESOR</span>
                  <div class="bold">LÓPEZ SANTANA SEBASTIAN</div>
                </td>
                <td colspan="2">
                  <span class="label-tiny">17/ TELÉFONO</span>
                  <div class="bold text-right" style="padding-right: 10px;">7228353322</div>
                </td>
              </tr>
              <tr>
                <td colspan="2" style="height: 30px;">
                  <span class="label-tiny">18/ DOMICILIO</span>
                  <div>San Lucas, Amanalco; Méx</div>
                </td>
                <td colspan="2">
                  <span class="label-tiny">19/ LOCALIDAD O COLONIA</span>
                  <div class="text-right">San Lucas 2da secc.</div>
                </td>
              </tr>
            </table>
          </td>

          <td class="nested-wrapper" style="width: 35%;">
            <table style="width: 100%;">
              <tr>
                <td class="text-center"><span class="label-tiny">20/</span>MESES</td>
                <td class="text-center"><span class="label-tiny">21/</span>No. DE HORAS IMPARTIDAS</td>
              </tr>
              <tr>
                <td>FEBRERO</td>
                <td class="text-center">20</td>
              </tr>
              <tr>
                <td>MARZO</td>
                <td class="text-center">24</td>
              </tr>
              <tr>
                <td>ABRIL</td>
                <td class="text-center">15</td>
              </tr>
              <tr>
                <td>MAYO</td>
                <td class="text-center">24</td>
              </tr>
              <tr>
                <td>JUNIO</td>
                <td class="text-center">21</td>
              </tr>
              <tr>
                <td>JULIO</td>
                <td class="text-center">12</td>
              </tr>
              <tr class="bold">
                <td>TOTAL DE HORAS SEMESTRALES</td>
                <td class="text-center">116</td>
              </tr>
            </table>

            <table style="width: 100%; border-top: 2px solid black;">
              <tr class="bold text-center bg-header">
                <td class="text-left">DATOS ESTADÍSTICOS</td>
                <td>HOMBRES</td>
                <td>MUJERES</td>
                <td>TOTAL</td>
              </tr>
              <tr>
                <td>No. DE ALUMNOS INSCRITOS</td>
                <td class="text-center">10</td>
                <td class="text-center">13</td>
                <td class="text-center">23</td>
              </tr>
              <tr>
                <td>BAJAS DURANTE EL AÑO</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
              </tr>
              <tr>
                <td>EXISTENCIA AL FINAL DEL AÑO</td>
                <td class="text-center">10</td>
                <td class="text-center">13</td>
                <td class="text-center">23</td>
              </tr>
              <tr>
                <td>No. DE APROBADOS</td>
                <td class="text-center">4</td>
                <td class="text-center">6</td>
                <td class="text-center">10</td>
              </tr>
              <tr>
                <td>No. REPROBADOS</td>
                <td class="text-center">1</td>
                <td class="text-center">0</td>
                <td class="text-center">1</td>
              </tr>
              <tr>
                <td>% DE ALUMNOS APROBADOS</td>
                <td class="text-center">40.00</td>
                <td class="text-center">46.15</td>
                <td class="text-center">43.08</td>
              </tr>
              <tr>
                <td>% DE ALUMNOS REPROBADOS</td>
                <td class="text-center">60.00</td>
                <td class="text-center">53.85</td>
                <td class="text-center">56.92</td>
              </tr>
              <tr>
                <td>SUMA DE CALIFICACIONES</td>
                <td class="text-center">35</td>
                <td class="text-center">52</td>
                <td class="text-center">87</td>
              </tr>
              <tr>
                <td>PROMEDIO DE CALIFICACIONES</td>
                <td class="text-center">3.50</td>
                <td class="text-center">4.00</td>
                <td class="text-center">3.78</td>
              </tr>
              <tr>
                <td colspan="2">METAS INSTITUCIONALES LOGRADAS</td>
                <td colspan="2" class="text-center">NO</td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="nested-wrapper">
            <table style="width: 100%;">
              <tr>
                <td style="width: 35%;">
                  <span class="label-tiny">20/ ASIGNATURA</span>
                  <div class="bold" style="font-size: 8px;">SUBMÓDULO II. DISEÑA Y ADMINISTRA BASES DE DATOS SIMPLES
                  </div>
                </td>
                <td style="width: 15%;">
                  <span class="label-tiny">21/ CURSO</span>
                  <div class="text-center">ORDINARIO</div>
                </td>
                <td style="width: 5%;">
                  <span class="label-tiny">22/ GRUPO</span>
                  <div class="text-center bold">21</div>
                </td>
                <td style="width: 45%; font-size: 8px;">
                  <span class="label-tiny">18/ HORARIO</span>
                  <div style="display: grid; grid-template-columns: 1fr 1fr;">
                    <div>LUNES</div>
                    <div>JUEVES</div>
                    <div>MARTES <span style="margin-left: 10px;">7:30-9:10, 12:00-12:50</span></div>
                    <div>VIERNES <span style="margin-left: 10px;">12:00-14:30</span></div>
                    <div>MIERCOLES</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="3">
                  <span class="label-tiny">23/ BACHILLERATO O CARRERA</span>
                  <div class="text-center bold">TÉCNICO EN INFORMÁTICA</div>
                </td>
                <td>
                  <span class="label-tiny">24/ SEMESTRE</span>
                  <div class="text-center bold">2</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="nested-wrapper">
            <table style="width: 100%;">
              <thead>
                <tr style="height: 120px;">
                  <th class="col-list">
                    <div class="vertical-wrapper">
                      <div class="vertical-text">No. DE LISTA</div>
                    </div>
                  </th>

                  <th class="col-sex">
                    <div class="vertical-wrapper">
                      <div class="vertical-text">SEXO</div>
                    </div>
                  </th>

                  <th class="col-name">NOMBRE DEL ALUMNO (A)</th>

                  <th style="padding: 0; width: 75px;">
                    <table style="width: 100%; height: 100%; border: none;">
                      <tr>
                        <td colspan="3" style="border:none; border-bottom: 1px solid black; height: 80px;">
                          <div class="vertical-wrapper" style="height: 80px;">
                            <div class="vertical-text" style="width: 120px;">FALTAS DE ASISTENCIA</div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td style="border:none; border-right:1px solid black; height: 40px;" class="text-center">1a</td>
                        <td style="border:none; border-right:1px solid black;" class="text-center">2a</td>
                        <td style="border:none;" class="text-center">3a</td>
                      </tr>
                    </table>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="vertical-text" style="font-size: 8px;">TOT. FALTAS</div>
                    </div>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="vertical-text" style="font-size: 7px;">% INASISTENCIA</div>
                    </div>
                  </th>

                  <th style="padding: 0; width: 75px;">
                    <table style="width: 100%; height: 100%; border: none;">
                      <tr>
                        <td colspan="3" style="border:none; border-bottom: 1px solid black; height: 80px;">
                          <div class="vertical-wrapper" style="height: 80px;">
                            <div class="vertical-text">EVALUACIONES</div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td style="border:none; border-right:1px solid black; height: 40px;" class="text-center">1a</td>
                        <td style="border:none; border-right:1px solid black;" class="text-center">2a</td>
                        <td style="border:none;" class="text-center">3a</td>
                      </tr>
                    </table>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="vertical-text">SUMA</div>
                    </div>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="vertical-text">PROMEDIO</div>
                    </div>
                  </th>

                  <th style="width: 100px;" class="text-center">OBSERVACIONES</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">1</td>
                  <td class="text-center">M</td>
                  <td>ARRIAGA ARRIAGA CHRISTOPHER</td>
                  <td class="col-grade">0</td>
                  <td class="col-grade"></td>
                  <td class="col-grade"></td>
                  <td class="col-grade">0</td>
                  <td class="col-grade">0.0</td>
                  <td class="col-grade">8</td>
                  <td></td>
                  
                </tr>
                <tr>
                  <td class="text-center">2</td>
                  <td class="text-center">M</td>
                  <td>ALVAREZ COLIN LUIS ENRIQUE</td>
                  <td class="col-grade">5</td>
                  <td class="col-grade"></td>
                  <td class="col-grade"></td>
                  <td class="col-grade">5</td>
                  <td class="col-grade">4.3</td>
                  <td class="col-grade">4.3</td>
                  <td></td>
                </tr>
                <tr>
                  <td class="text-center">3</td>
                  <td class="text-center">F</td>
                  <td>ARRIAGA MARTINEZ LIZBETH</td>
                  <td class="col-grade">0</td>
                  <td class="col-grade"></td>
                  <td class="col-grade"></td>
                  <td class="col-grade">0</td>
                  <td class="col-grade">0.0</td>
                  <td class="col-grade">7</td>
                  <td></td>
                </tr>
                <tr>
                  <td class="text-center">4</td>
                  <td class="text-center">M</td>
                  <td>AVILA BARTOLO EFRAIN</td>
                  <td class="col-grade">2</td>
                  <td></td>
                  <td></td>
                  <td class="col-grade">2</td>
                  <td class="col-grade">1.7</td>
                  <td class="col-grade">8</td>
                  <td></td>
                </tr>
                <tr>
                  <td class="text-center">5</td>
                  <td class="text-center">F</td>
                  <td>FAVILA CASTILLO STEPHANIE</td>
                  <td class="col-grade">0</td>
                  <td></td>
                  <td></td>
                  <td class="col-grade">0</td>
                  <td class="col-grade">0.0</td>
                  <td class="col-grade">9</td>
                  <td></td>
                </tr>
                <tr>
                  <td class="text-center">6</td>
                  <td class="text-center">M</td>
                  <td>CARBAJAL NIETO CESAR JAVIER</td>
                  <td class="col-grade">0</td>
                  <td></td>
                  <td></td>
                  <td class="col-grade">0</td>
                  <td class="col-grade">0.0</td>
                  <td class="col-grade">7</td>
                  <td></td>
                </tr>
                <tr>
                  <td class="text-center">7</td>
                  <td class="text-center">F</td>
                  <td>CASTILLO ALBARRAN ALEXA</td>
                  <td class="col-grade">0</td>
                  <td></td>
                  <td></td>
                  <td class="col-grade">0</td>
                  <td class="col-grade">0.0</td>
                  <td class="col-grade">8</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>

    </div>

  </div>
</template>

<script>
import logo from '@asset/img/Imagen1.png';

import html2pdf from 'html2pdf.js'
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api' // Asegúrate que la ruta a tu cliente api es correcta

export default {
  name: 'RegistroEscolarPDF',
  setup() {
    const route = useRoute()
    const pdfContent = ref(null)
    const students = ref([])
    const headerData = ref({})
    const loading = ref(false)
    const error = ref(null)
    const seguimientoId = ref(route.params.id) // Asume que el id viene de la ruta /reporte/:id

    const fetchData = async () => {
      if (!seguimientoId.value) {
        error.value = "No se ha proporcionado un ID de seguimiento."
        return
      }
      loading.value = true
      error.value = null
      try {
        // Petición para los datos del encabezado
        const headerResponse = await api.get(`/listas/seguimiento/${seguimientoId.value}`)
        headerData.value = headerResponse.data.Seguimiento

        // Petición para los datos de los estudiantes (formato1)
        const studentsResponse = await api.get(`/seguimientos/formato1/${seguimientoId.value}`)
        students.value = studentsResponse.data
      } catch (err) {
        console.error("Error al obtener los datos del reporte:", err)
        error.value = "No se pudieron cargar los datos del reporte. Verifique la consola para más detalles."
      } finally {
        loading.value = false
      }
    }

    const currentDate = computed(() => {
      return new Date().toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    })

    const generarPDF = () => {
      // if (loading.value || error.value) return;

      loading.value = true
      const element = pdfContent.value
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

      html2pdf().from(element).set(options).save().then(() => {
        loading.value = false
      }).catch(err => {
        console.error("Error al generar el PDF:", err)
        error.value = "Ocurrió un error al generar el PDF."
        loading.value = false
      })
    }

    onMounted(fetchData)

    return {
      pdfContent,
      students,
      headerData,
      loading,
      error,
      currentDate,
      generarPDF,
      logo,
    }
  }
};
</script>

<style>
@page {
  size: A4 landscape;
  margin: 0.5cm;
}

body {
  font-family: Arial, sans-serif;
  font-size: 9px;
  /* Letra base muy pequeña para que quepa todo */
  margin: 0;
  padding: 0;
  -webkit-print-color-adjust: exact;
}

/* ESTILOS DE TABLA */
table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
}

td,
th {
  /* border: 1px solid black; */
  padding: 2px 4px;
  vertical-align: middle;
}

/* CLASES UTILITARIAS */
.text-center {
  text-align: center;
}

.text-left {
  text-align: left;
}

.text-right {
  text-align: right;
}

.bold {
  font-weight: bold;
}

.no-border-top {
  border-top: none !important;
}

.no-border-bottom {
  border-bottom: none !important;
}

.no-border-left {
  border-left: none !important;
}

.no-border-right {
  border-right: none !important;
}

/* ETIQUETAS PEQUEÑAS (Ej: "1/ DIRECCIÓN") */
.label-tiny {
  font-size: 6px;
  color: #333;
  display: block;
  margin-bottom: 1px;
  line-height: 1;
}

/* --- CORRECCIÓN: TEXTO VERTICAL SIN WRITING-MODE --- */

/* 1. Ajuste a las celdas contenedoras (TH) */
.col-list,
.col-sex,
.col-grade {
  height: 120px;
  /* Altura forzada */
  vertical-align: middle;
  padding: 0 !important;
  /* Quitamos padding para usar flex */
  position: relative;
}

/* 2. Contenedor FLEX para centrar antes de rotar */
.vertical-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  width: 100%;
  overflow: hidden;
}

/* 3. Rotación manual (-90 grados) */
.vertical-text {
  transform: rotate(-90deg);
  white-space: nowrap;
  display: block;
  font-size: 9px;
  text-align: center;
  /* Truco: Ancho "falso" mayor para que quepa el texto antes de girar */
  /* width: 100px; */
}

/* Ajustes de ancho de columnas */
.col-list {
  width: 30px;
}

.col-sex {
  width: 30px;
}

.col-grade {
  width: 35px;
}

/* ENCABEZADO INSTITUCIONAL (LOGO Y TITULOS) */
.header-container {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
}

.header-logo {
  width: 60px;
  height: 60px;
  border: 1px solid #ccc;
  /* Placeholder borde logo */
  margin-right: 15px;
}

.header-titles h2,
.header-titles h3 {
  margin: 2px 0;
  text-align: left;
}

/* COLORES */
.bg-header {
  background-color: #f2f2f2;
}

/* AJUSTES ESPECÍFICOS DE ANCHO */
.col-list {
  width: 20px;
}

.col-sex {
  width: 20px;
}

.col-name {
  width: 250px;
}

.col-grade {
  width: 25px;
  text-align: center;
}

/* TABLA ANIDADA SIN BORDES EXTERNOS */
.nested-table td {
  /* border: 1px solid black; */
}

.nested-wrapper {
  padding: 0 !important;
  border: none !important;
  vertical-align: top;
}
</style>