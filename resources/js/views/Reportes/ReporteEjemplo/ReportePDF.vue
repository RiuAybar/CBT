<template>
  <div class="container mt-4">
    <h2 class="mb-4">Generador de Registro Escolar {{ $route.params.id }}</h2>
    <button class="btn btn-primary m-2" @click="generarPDF" :disabled="loading">
      <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      {{ loading ? 'Generando...' : 'Generar PDF' }}
    </button>

    <button class="btn btn-success m-2" @click="fetchData" :disabled="loading">
      <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      {{ loading ? 'Generando...' : 'Recargar información' }}
    </button>
    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>
    <!-- Contenedor principal para el PDF -->
    <div ref="pdfContent" class="pdf-container container">

      <div class="m-3">
        <table style="border: 2px solid #FFFFFF;">
          <thead>
            <tr>
              <th style="border: 2px solid #FFFFFF;">
                <img :src="logo" width="120" height="30">
              </th>
              <th class="px-2" style="border: 2px solid #FFFFFF;">
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
          <td colspan="2" class="text-center bold bg-header"
            style="font-size: 12px; border-bottom: 2px solid black; padding: 5px;">
            REGISTRO DE FALTAS DE ASISTENCIA , CALIFICACIONES Y PROMEDIO POR ASIGNATURA
          </td>
        </tr>

        <tr>
          <td class="nested-container" style="width: 60%; border-right: 2px solid black;">
            <table class="nested-table">
              <tr>
                <td colspan="4"><span class="label-tiny">1/ DIRECCIÓN</span>
                  <div class="text-center bold">GENERAL DE EDUCACIÓN MEDIA SUPERIOR</div>
                </td>
              </tr>
              <tr>
                <td colspan="4"><span class="label-tiny">2/ DEPARTAMENTO</span>
                  <div class="text-center">DE BACHILLERATO TECNOLÓGICO</div>
                </td>
              </tr>
              <tr>
                <td colspan="4" style="height: 35px;"><span class="label-tiny">3/ NOMBRE DE LA ESCUELA</span>
                  <div class="text-center bold" style="font-size: 11px;">CBT, AMANALCO DE BECERRA</div>
                </td>
              </tr>
              <tr>
                <td colspan="2" style="width: 40%;"><span class="label-tiny">4/ TURNO</span>
                  <div class="text-center bold">DISCONTINUO</div>
                </td>
                <td colspan="2" style="width: 60%;"><span class="label-tiny">5/ NIVEL</span>
                  <div class="text-center bold">MEDIO SUPERIOR TÉCNICO</div>
                </td>
              </tr>

              <tr>
                <td><span class="label-tiny">8/ CLAVE CENTRO DE TRABAJO</span>
                  <div class="text-center bold">15ECT0112M</div>
                </td>
                <td><span class="label-tiny">9/ No. DE C.RE.S.E.</span>
                  <div class="text-center">NA</div>
                </td>
                <td colspan="2"><span class="label-tiny">10/ ZONA ESCOLAR</span>
                  <div class="text-center bold text-right">BT009</div>
                </td>
              </tr>
              <tr>
                <td colspan="2"><span class="label-tiny">11/ DOMICILIO</span>
                  <div class="bold text-center">DOMICILIO CONOCIDO S/N</div>
                </td>
                <td colspan="2"><span class="label-tiny">12/ LOCALIDAD O COLONIA</span>
                  <div class="bold text-center">EL POTRERO</div>
                </td>
              </tr>
              <tr>
                <td><span class="label-tiny">13/ MUNICIPIO</span>
                  <div class="bold text-center">AMANALCO</div>
                </td>
                <td><span class="label-tiny">14/ TELÉFONO</span>
                  <div class="bold text-center">7228353322</div>
                </td>
                <td colspan="2"><span class="label-tiny">15/ CICLO ESCOLAR</span>
                  <div class="bold text-center">2024-2025</div>
                  <!-- Cambiar -->
                </td>
              </tr>
              <tr>
                <td colspan="2"><span class="label-tiny">16/ NOMBRE DEL PROFESOR</span>
                  <div class="bold text-center">LÓPEZ SANTANA SEBASTIAN</div>
                  <!-- Agregar -->
                </td>
                <td colspan="2"><span class="label-tiny">17/ TELÉFONO</span>
                  <div class="bold text-center">7228353322</div>
                </td>
                <!-- Agregar -->
              </tr>
              <tr>
                <td colspan="2" class="h-30"><span class="label-tiny">18/ DOMICILIO</span>
                  <div class="bold text-left">San Lucas, Amanalco;Méx</div>
                  <!-- Agregar -->
                </td>
                <td colspan="2" class="h-30"><span class="label-tiny">19/ LOCALIDAD O COLONIA</span>
                  <div class="bold text-center">San Lucas 2da secc.</div>
                </td>
              </tr>

              <tr>
                <td colspan="2" style="width: 50%; vertical-align: top;">
                  <span class="label-tiny">20/ ASIGNATURA</span>
                  <div class="bold" style="font-size: 9px; padding-top: 5px;">
                    SUBMÓDULO II. DISEÑA Y ADMINISTRA BASES DE DATOS SIMPLES
                  </div>
                </td>
                <td style="width: 25%;">
                  <span class="label-tiny">21/ CURSO</span>
                  <div class="text-center" style="margin-top: 10px;">ORDINARIO</div>
                </td>
                <td style="width: 25%;">
                  <span class="label-tiny">22/ GRUPO</span>
                  <div class="text-center bold" style="margin-top: 10px;">2 1</div>
                </td>
              </tr>
              <tr>
                <td colspan="3" style="border-bottom: 1px solid black;">
                  <span class="label-tiny">23/ BACHILLERATO O CARRERA</span>
                  <div class="text-center bold">TÉCNICO EN INFORMÁTICA</div>
                </td>
                <td style="border-bottom: 1px solid black;">
                  <span class="label-tiny">24/ SEMESTRE</span>
                  <div class="text-center bold">2</div>
                </td>
              </tr>
            </table>
          </td>

          <td class="nested-container" style="width: 40%;">
            <table class="nested-table">
              <tr>
                <td style="width: 50%;" class="text-center"><span class="label-tiny">33/</span> MESES</td>
                <td style="width: 50%;" class="text-center"><span class="label-tiny">34/</span> No. DE HORAS IMPARTIDAS
                </td>
              </tr>
              <tr v-for="Mes in RegistroHorasDocencia" :key="Mes.id">
                <td>{{ Mes.mes }}</td>
                <td>{{ Mes.horasImpartidas }}</td>
              </tr>
              <tr>
                <td class="bold text-center">TOTAL DE HORAS SEMESTRALES</td>
                <td class="text-center bold">{{ totalHoras }}</td>
              </tr>
            </table>

            <table style="width: 100%; border-collapse: collapse;">
              <tr>
                <td style="width: 55%;  border-right: 1px solid black;" class="bold text-center">
                  <span class="label-tiny text-left">35/</span> DATOS ESTADÍSTICOS
                </td>
                <td style="width: 15%;" class="bold text-center">HOMBRES</td>
                <td style="width: 15%;" class="bold text-center">MUJERES</td>
                <td style="width: 15%; border-right: none;" class="bold text-center">TOTAL</td>
              </tr>
              <tr>
                <td style="">No. DE ALUMNOS INSCRITOS</td>
                <td class="text-center">10</td>
                <td class="text-center">13</td>
                <td class="text-center" style="border-right: none;">23</td>
              </tr>
              <tr>
                <td style="">BAJAS DURANTE EL AÑO</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center" style="border-right: none;">0</td>
              </tr>
              <tr>
                <td style="">EXISTENCIA AL FINAL DEL AÑO</td>
                <td class="text-center">10</td>
                <td class="text-center">13</td>
                <td class="text-center" style="border-right: none;">23</td>
              </tr>
              <tr>
                <td style="">No. DE APROBADOS</td>
                <td class="text-center">4</td>
                <td class="text-center">6</td>
                <td class="text-center" style="border-right: none;">10</td>
              </tr>
              <tr>
                <td style="">No. REPROBADOS</td>
                <td class="text-center">1</td>
                <td class="text-center">0</td>
                <td class="text-center" style="border-right: none;">1</td>
              </tr>
              <tr>
                <td style="">% DE ALUMNOS APROBADOS</td>
                <td class="text-center">40.00</td>
                <td class="text-center">46.15</td>
                <td class="text-center" style="border-right: none;">43.08</td>
              </tr>
              <tr>
                <td style="">% DE ALUMNOS REPROBADOS</td>
                <td class="text-center">60.00</td>
                <td class="text-center">53.85</td>
                <td class="text-center" style="border-right: none;">56.92</td>
              </tr>
              <tr>
                <td style="">SUMA DE CALIFICACIONES</td>
                <td class="text-center">35</td>
                <td class="text-center">52</td>
                <td class="text-center" style="border-right: none;">87</td>
              </tr>
              <tr>
                <td style="">PROMEDIO DE CALIFICACIONES</td>
                <td class="text-center">3.50</td>
                <td class="text-center">4.00</td>
                <td class="text-center" style="border-right: none;">3.78</td>
              </tr>
              <tr>
                <td style="">METAS INSTITUCIONALES LOGRADAS</td>
                <td colspan="3" class="text-center" style="border-right: none;">NO</td>
              </tr>
            </table>

            <table style="width: 100%; border-collapse: collapse;">
              <tr>
                <td colspan="2" style=" border-right: none; border-top: 1px solid black;">
                  <span class="label-tiny">38/ HORARIO</span>
                </td>
              </tr>
              <tr>
                <td class="schedule-cell" style="width: 50%; ">LUNES</td>
                <td class="schedule-cell" style="width: 50%; border-right: none;">JUEVES</td>
              </tr>
              <tr>
                <td class="schedule-cell" style="">
                  MARTES <span style="margin-left: 5px;">7:30-9:10, 12:00-12:50</span>
                </td>
                <td class="schedule-cell" style="border-right: none;"></td>
              </tr>
              <tr>
                <td class="schedule-cell" style=" border-bottom: none;">MIERCOLES</td>
                <td class="schedule-cell" style="border-right: none; border-bottom: none;">
                  VIERNES <span style="margin-left: 5px;">12:00-14:30</span>
                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>

      <table style="border: 1px solid black">
        <tr>
          <td colspan="2" class="nested-container">
            <table class="nested-table">
              <thead>
                <tr style="height: 140px">
                  <th class="col-list">
                    <div class="vertical-wrapper">
                      <div class="">No. DE LISTA</div>
                    </div>
                  </th>

                  <th class="col-sex">
                    <div class="vertical-wrapper">
                      <div class="">SEXO</div>
                    </div>
                  </th>

                  <th style="width: 250px" class="text-center">
                    NOMBRE DEL ALUMNO (A)
                  </th>

                  <th style="padding: 0; width: 90px">
                    <table style="width: 100%; height: 100%; border: none">
                      <tr>
                        <td colspan="3" style="
                          border: none;
                          border-bottom: 1px solid black;
                          height: 100px;
                        ">
                          <div class="vertical-wrapper">
                            <div class="" style="width: 120px">
                              FALTAS DE ASISTENCIA
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td style="
                          border: none;
                          border-right: 1px solid black;
                          height: 40px;
                        " class="text-center">
                          1a
                        </td>
                        <td style="border: none; border-right: 1px solid black" class="text-center">
                          2a
                        </td>
                        <td style="border: none" class="text-center">3a</td>
                      </tr>
                    </table>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="">TOT. DE FALTAS</div>
                    </div>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="" style="width: 120px">
                        % DE INASISTENCIA
                      </div>
                    </div>
                  </th>

                  <th style="padding: 0; width: 90px">
                    <table style="width: 100%; height: 100%; border: none">
                      <tr>
                        <td colspan="3" style="
                          border: none;
                          border-bottom: 1px solid black;
                          height: 100px;
                        ">
                          <div class="vertical-wrapper">
                            <div class="">EVALUACIONES</div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td style="
                          border: none;
                          border-right: 1px solid black;
                          height: 40px;
                        " class="text-center">
                          1a
                        </td>
                        <td style="border: none; border-right: 1px solid black" class="text-center">
                          2a
                        </td>
                        <td style="border: none" class="text-center">3a</td>
                      </tr>
                    </table>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="">SUMA</div>
                    </div>
                  </th>

                  <th class="col-grade">
                    <div class="vertical-wrapper">
                      <div class="">PROMEDIO</div>
                    </div>
                  </th>

                  <th class="text-center">OBSERVACIONES</th>
                </tr>
              </thead>

              <tbody>
                <tr style="height: 20px" v-for="alumno in alumnos" :key="alumno.id">
                  <td class="text-center">{{ alumno.No_DE_LISTA }}</td>
                  <td class="text-center">{{ alumno.sexo }}</td>
                  <td style="padding-left: 5px">{{ alumno.NOMBRE_DEL_ALUMNO }}</td>

                  <td style="padding: 0; width: 90px">
                    <table style="width: 100%; height: 100%; border: none">
                      <tr>
                        <td style="
                          border: none;
                          border-right: 1px solid black;
                          height: 40px;
                        " class="text-center">
                          {{ alumno.faltas_Parcial_1 }}
                        </td>
                        <td style="border: none; border-right: 1px solid black" class="text-center">
                          {{ alumno.faltas_Parcial_2 }}
                        </td>
                        <td style="border: none" class="text-center">
                          {{ alumno.faltas_Parcial_3 }}
                        </td>
                      </tr>
                    </table>
                  </td>

                  <td class="text-center">{{ alumno.FALTAS }}</td>
                  <td class="text-center">0</td>

                  <td style="padding: 0; width: 90px">
                    <!-- <table style="width: 100%; height: 100%; border: none">
                      <tr>
                        <td style="
                          border: none;
                          border-right: 1px solid black;
                          height: 40px;
                        " class="text-center">
                          {{ alumno.eval_Parcial_1 }}
                        </td>
                        <td style="border: none; border-right: 1px solid black" class="text-center">
                          {{ alumno.eval_Parcial_2 }}
                        </td>
                        <td style="border: none" class="text-center">
                          {{ alumno.eval_Parcial_3 }}
                        </td>
                      </tr>
                    </table> -->
                  </td>

                  <!-- <td class="text-center">{{ alumno.suma_Parcial_1 }}</td> -->

                  <!-- <td class="text-center">{{ alumno.faltas_Parcial_2 }}</td> -->
                  <!-- <td class="text-center">{{ alumno.eval_Parcial_2 }}</td> -->
                  <!-- <td class="text-center">{{ alumno.suma_Parcial_2 }}</td> -->
                </tr>
                <tr style="height: 20px">
                  <td class="text-center">1</td>
                  <td class="text-center">H</td>
                  <td style="padding-left: 5px">EJEMPLO PÉREZ JUAN</td>
                  <td class="text-center"></td>
                  <td class="text-center"></td>
                  <td class="text-center"></td>
                  <td class="text-center"></td>
                  <td class="text-center"></td>
                  <td class="text-center"></td>

                  <td></td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>

      <!-- Firmas -->
      <div class="firmas-container mt-5">
        <div class="text-center mb-3">
          <p>El Potrero, Amanalco, México {{ new Date().toLocaleDateString('es-MX') }}</p>
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
    const alumnos = ref([]);
    const estadisticas = ref([]);
    const parciales = ref([]);
    const RegistroHorasDocencia = ref([]);

    const parcialesObjeto = (parciales) => {
      if (!Array.isArray(parciales)) return {};
      return parciales.map(item => {
        // Reemplaza espacios por "_" y asegura el formato correcto
        return typeof item === 'string'
          ? item.replace(/\s+/g, '_')
          : item;
      });
    }

    const fetchData = async () => {
      console.log(seguimientoId);
      if (!seguimientoId.value) {
        error.value = "No se ha proporcionado un ID de seguimiento."
        return
      }
      loading.value = true
      error.value = null
      try {

        const response = await api.get(`/Registro/Seguimiento/${seguimientoId.value}/formato1`);
        const { alumnos: alumnosApi, estadisticas, parciales, RegistroHoras } = response.data;
        alumnos.value = alumnosApi;
        estadisticas.value = estadisticas;
        parciales.value = parcialesObjeto(parciales);
        RegistroHorasDocencia.value = RegistroHoras;
        console.log(alumnosApi);
        // // Petición para los datos del encabezado
        // const headerResponse = await api.get(`/listas/seguimiento/${seguimientoId.value}`)
        // headerData.value = headerResponse.data.Seguimiento

        // // Petición para los datos de los estudiantes (formato1)
        // const studentsResponse = await api.get(`/seguimientos/formato1/${seguimientoId.value}`)
        // students.value = studentsResponse.data
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

    const totalHoras = computed(() => {
      return RegistroHorasDocencia.value.reduce((acc, item) => {
        return acc + (item.horasImpartidas || 0);
      }, 0);
    });

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
      alumnos,
      RegistroHorasDocencia,
      totalHoras,
      pdfContent,
      students,
      headerData,
      loading,
      error,
      currentDate,
      generarPDF,
      fetchData,
      logo,
    }
  }
};
</script>

<style scoped>
.pdf-container {
  font-family: Arial, sans-serif;
  font-size: 7px;
  margin: 20px;
}

/* ESTILOS DE TABLA GENERAL */
table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
}

td,
th {
  border: 1px solid black;
  padding: 3px 4px;
  vertical-align: middle;
}

/* CLASES DE UTILIDAD */
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

/* Configuración para tablas anidadas (Layout) */
.nested-container {
  padding: 0 !important;
  border: none !important;
  vertical-align: top;
}

.nested-table {
  width: 100%;
  border: none;
}

.nested-table td {
  border: 1px solid black;
  /* Quitamos bordes laterales externos */

  border-right: none;
}

/* Eliminar bordes duplicados en la primera y última fila de las anidadas */
.nested-table tr:first-child td {
  border-top: none;
}

.nested-table tr:last-child td {
  border-bottom: none;
}

/* ETIQUETAS PEQUEÑAS (Ej: "1/ DIRECCIÓN") */
.label-tiny {
  font-size: 6px;
  color: #444;
  display: block;
  line-height: 1;
  margin-bottom: 2px;
  text-align: left;
}

/* Filas con altura mínima específica */
.h-40 {
  height: 40px;
}

.h-30 {
  height: 30px;
}

/* Estilo específico para el horario */
.schedule-cell {
  font-size: 9px;
  height: 20px;
  /* Altura fija para filas de horario */
  vertical-align: top;
}
</style>