<template>
    <div>
        <div class="container-fluid p-0">
            <button @click="crear()" class="btn btn-primary float-end mt-n1"
                v-if="hasPermission('puede crear seguimientos')">
                <i class="align-middle me-2" data-feather="plus-circle"></i>
                Agregar Seguimiento
            </button>
            <h1 class="h3 mb-3">
                <strong>
                    Seguimiento
                </strong>
            </h1>
            <div class="row">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title mb-0">
                                        Lista de seguimiento
                                    </h5>
                                </div>
                                <div class="col-sm-6">
                                    <input name="busqueda" v-model="busqueda" placeholder="Buscar en servidor..."
                                        class="form-control mb-3" />
                                </div>
                            </div>
                        </div>

                        <EasyDataTable :headers="headersFiltrados" :items="Seguimientos" :loading="cargando"
                            :rows-per-page="5" table-class="table table-hover my-0">
                            <!-- 🎯 Columna de acciones personalizada -->
                            <template
                                v-if="hasPermission('puede editar seguimientos') || hasPermission('ver listas') || hasPermission('ver f1')"
                                #item-action="{ id, nombre }">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary me-1" @click="editar(id)"
                                        v-if="hasPermission('puede editar seguimientos')">
                                        Editar
                                    </button>
                                    <router-link :to="`lista/${id}/edit`" class="btn btn-sm btn-outline-secondary me-1"
                                        v-if="hasPermission('ver listas')">
                                        ver lista
                                    </router-link>
                                    <button class="btn btn-sm btn-outline-info me-1" @click="formato1(id)"
                                        v-if="hasPermission('ver f1')">
                                        F1
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>

                    </div>
                </div>
            </div>
        </div>

        <Modal size="lg" ref="modalSeguimiento" id="modal-seguimiento"
            :title="Seguimiento.id ? 'Editar Seguimiento' : 'Agregar Seguimiento'">
            <!-- Contenido dinámico: slot principal -->
            <template #default>
                <div class="row">
                    <div class="mb-3 col-sm-4">
                        <label for="profesor_id" class="form-label">Profesor</label>
                        <v-select input-id="profesor_id" v-model="selectProfesor" :options="selectOptionsProfesor"
                            label="text" :filterable="false" :loading="selectLoading"
                            placeholder="Seleccione un profesor" @search="selectProfesorOptions"
                            :reduce="option => option" no-options="Seleccione una opción"
                            no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                        <div v-if="errores.profesor_id" class="form-text text-danger">
                            {{ errores.profesor_id[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="materia_id" class="form-label">Materia</label>
                        <v-select input-id="materia_id" v-model="selectMateria" :options="selectOptionsMateria"
                            label="text" :filterable="false" :loading="selectLoadingMateria"
                            placeholder="Seleccione un profesor" @search="selectMateriaOptions"
                            :reduce="option => option" no-options="Seleccione una opción"
                            no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                        <div v-if="errores.materia_id" class="form-text text-danger">
                            {{ errores.materia_id[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="semestre_id" class="form-label">Semestre</label>
                        <v-select input-id="semestre_id" v-model="selectSemestre" :options="selectOptionsSemestre"
                            label="text" :filterable="false" :loading="selectLoadingSemestre"
                            placeholder="Seleccione un profesor" @search="selectSemestreOptions"
                            :reduce="option => option" no-options="Seleccione una opción"
                            no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                        <div v-if="errores.semestre_id" class="form-text text-danger">
                            {{ errores.semestre_id[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="grupo_id" class="form-label">Grado/Grupo</label>
                        <v-select input-id="grupo_id" v-model="selectGrupo" :options="selectOptionsGrupo" label="text"
                            :filterable="false" :loading="selectLoadingGrupo" placeholder="Seleccione un profesor"
                            @search="selectGrupoOptions" :reduce="option => option" no-options="Seleccione una opción"
                            no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                        <div v-if="errores.grupo_id" class="form-text text-danger">
                            {{ errores.grupo_id[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="carrera_id" class="form-label">Carrera</label>
                        <v-select input-id="carrera_id" v-model="selectCarrera" :options="selectOptionsCarrera"
                            label="text" :filterable="false" :loading="selectLoadingCarrera"
                            placeholder="Seleccione un profesor" @search="selectCarreraOptions"
                            :reduce="option => option" no-options="Seleccione una opción"
                            no-results="No se encontraron resultados" :selectable="option => !option.disabled" />
                        <div v-if="errores.carrera_id" class="form-text text-danger">
                            {{ errores.carrera_id[0] }}
                        </div>
                    </div>
                    <div class="mb-3 col-sm-4">
                        <label for="ano" class="form-label">Año</label>
                        <input v-model="Seguimiento.ano" type="text" class="form-control" id="ano"
                            aria-describedby="Año" @input="validarAno">
                        <div v-if="errores.ano" class="form-text text-danger">
                            {{ errores.ano[0] }}
                        </div>
                    </div>
                </div>
            </template>

            <!-- Footer dinámico: slot con nombre -->
            <template #footer>
                <button v-if="Seguimiento.id" class="btn btn-success" @click="guardarCambios(Seguimiento.id)">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Guardar Cambios
                </button>
                <button v-else class="btn btn-success" @click="agregar()">
                    <i class="align-middle me-2" data-feather="save"></i>
                    Agregar
                </button>
            </template>
        </Modal>

        <div class="container" ref="pdfContent"
            style="visibility: visible; position: static; left: 0Ppx; width: 210mm;">
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
                    <td colspan="2" class="text-center bold bg-header"
                        style="font-size: 10px; border-bottom: 2px solid black;">
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
                                    <div class="bold" style="font-size: 8px;">SUBMÓDULO II. DISEÑA Y ADMINISTRA BASES DE
                                        DATOS
                                        SIMPLES</div>
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
                                <tr style="height: 0px;">
                                    <th class="col-list" style="white-space: nowrap;">
                                        <div class="vertical-text">No. DE LISTA</div>
                                    </th>
                                    <th class="col-sex">
                                        <div class="vertical-text">SEXO</div>
                                    </th>
                                    <th class="col-name">NOMBRE DEL ALUMNO (A)</th>

                                    <th style="padding: 0; width: 75px;">
                                        <table style="width: 100%; height: 100%; border: none;">
                                            <tr>
                                                <td colspan="3"
                                                    style="border:none; border-bottom: 1px solid black; height: 80px;">
                                                    <div class="vertical-text" style="height: 80px;">FALTAS
                                                        DE<br>ASISTENCIA
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border:none; border-right:1px solid black; height: 40px;">1a
                                                </td>
                                                <td style="border:none; border-right:1px solid black;">2a</td>
                                                <td style="border:none;">3a</td>
                                            </tr>
                                        </table>
                                    </th>

                                    <th style="width: 25px;">
                                        <div class="vertical-text">TOT. DE FALTAS</div>
                                    </th>
                                    <th style="width: 25px;">
                                        <div class="vertical-text">% DE INASISTENCIA</div>
                                    </th>

                                    <th style="padding: 0; width: 75px;">
                                        <table style="width: 100%; height: 100%; border: none;">
                                            <tr>
                                                <td colspan="3"
                                                    style="border:none; border-bottom: 1px solid black; height: 80px;">
                                                    <div class="vertical-text" style="height: 0px;">EVALUACIONES</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border:none; border-right:1px solid black; height: 40px;">1a
                                                </td>
                                                <td style="border:none; border-right:1px solid black;">2a</td>
                                                <td style="border:none;">3a</td>
                                            </tr>
                                        </table>
                                    </th>

                                    <th style="width: 25px;">
                                        <div class="vertical-text">SUMA</div>
                                    </th>
                                    <th style="width: 50px;">
                                        <div class="vertical-text">PROMEDIO</div>
                                    </th>
                                    <th style="width: 100px;">OBSERVACIONES</th>
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
                                    <td class="col-grade"></td>
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
                                    <td class="col-grade">5</td>
                                    <td class="col-grade"></td>
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
                                    <td class="col-grade"></td>
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
import api from '../../../services/api';
import Modal from '../../../components/Modal.vue';

import html2pdf from 'html2pdf.js';

import 'vue3-easy-data-table/dist/style.css';
import EasyDataTable from 'vue3-easy-data-table';

import { mapGetters } from 'vuex';
import debounce from 'lodash/debounce';

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: 'Seguimiento',
    components: {
        EasyDataTable,
        Modal,
        vSelect
    },
    data() {
        return {
            logo,
            Seguimientos: [],
            cargando: false,
            Seguimiento: {
                id: null,
                materia_id: null,
                semestre_id: null,
                grupo_id: null,
                carrera_id: null,
                profesor_id: null,
                ano: null,
            },
            busqueda: '',
            errores: {},

            selectProfesor: null,
            selectOptionsProfesor: [],
            selectLoading: false,

            selectMateria: null,
            selectOptionsMateria: [],
            selectLoadingMateria: false,

            selectSemestre: null,
            selectOptionsSemestre: [],
            selectLoadingSemestre: false,

            selectGrupo: null,
            selectOptionsGrupo: [],
            selectLoadingGrupo: false,

            selectCarrera: null,
            selectOptionsCarrera: [],
            selectLoadingCarrera: false,
            //pdf
            alumnos: [],
            parciales: [],
            estadisticas: {},
            seguimientoId: 1
        }
    },
    watch: {
        busqueda: {
            // 👀 Observa cada cambio en la búsqueda
            handler: debounce(function (val) {
                this.consultar(val);
            }, 300),
            immediate: true
        },
        selectedYear(newYear, oldYear) {
            this.consultar(this.busqueda);
        }
    },
    computed: {
        ...mapGetters(['selectedYear']), // trae selectedYear del store
        ...mapGetters('auth', ['hasPermission']),
        headersFiltrados() {
            const base = [
                { text: 'Id', value: 'id' },
                { text: 'Profesores', value: 'profesores' },
                { text: 'Materias', value: 'materias' },
                { text: 'Semestres', value: 'semestres' },
                { text: 'Grados/Grupos', value: 'grupos' },
                { text: 'Carreras', value: 'carreras' },
                { text: 'Año', value: 'ano' },
            ];
            if (this.hasPermission('puede editar seguimientos') || this.hasPermission('ver listas') || this.hasPermission('ver f1')) {
                base.push({ text: 'Acciones', value: 'action' });
            }
            return base;
        }
    },
    methods: {
        async consultar(filtro = '') {
            this.cargando = true;
            try {
                const res = await api.get('/Registro/Seguimiento', {
                    params: {
                        search: filtro,
                        ano: this.selectedYear
                    }
                });
                this.Seguimientos = res.data;
            } catch (error) {
                console.error('Error al consultar:', error);
            } finally {
                this.cargando = false;
            }
        },
        crear() {
            this.errores = {}; // 🔄 Limpia los errores
            this.Seguimiento = this.selectOptionNull();
            this.selectMateria = null;
            this.selectSemestre = null;
            this.selectGrupo = null;
            this.selectCarrera = null;
            this.selectProfesor = null;
            this.Seguimiento.ano = this.selectedYear;
            this.$refs.modalSeguimiento.abrir();
        },
        async agregar() {
            try {
                this.Seguimiento.materia_id = this.selectMateria?.id;
                this.Seguimiento.semestre_id = this.selectSemestre?.id;
                this.Seguimiento.grupo_id = this.selectGrupo?.id;
                this.Seguimiento.carrera_id = this.selectCarrera?.id;
                this.Seguimiento.profesor_id = this.selectProfesor?.id;
                await api.post('/Registro/Seguimiento', this.Seguimiento);
                this.consultar();
                this.$refs.modalSeguimiento.cerrar();

                this.Seguimiento = this.selectOptionNull();
                this.errores = {}; // 🔄 Limpia los errores
                this.$swal.fire('Éxito', '✅ Registro agregado correctamente', 'success');
            } catch (error) {

                if (error.response && error.response.status == 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
                }

            }
        },
        editar(id) {
            this.errores = {}; // 🔄 Limpia los errores
            const encontrado = this.Seguimientos.find(p => p.id === id);
            console.log(encontrado);
            if (encontrado) {
                this.Seguimiento = { ...encontrado };
                this.selectMateria = {
                    id: this.Seguimiento.materia_id,
                    text: this.Seguimiento.materias
                };
                this.selectSemestre = {
                    id: this.Seguimiento.semestre_id,
                    text: this.Seguimiento.semestres
                };
                this.selectGrupo = {
                    id: this.Seguimiento.grupo_id,
                    text: this.Seguimiento.grupos
                };
                this.selectCarrera = {
                    id: this.Seguimiento.carrera_id,
                    text: this.Seguimiento.carreras
                };
                this.selectProfesor = {
                    id: this.Seguimiento.profesor_id,
                    text: this.Seguimiento.profesores
                };
                this.$refs.modalSeguimiento.abrir();
            }
        },
        async guardarCambios(id) {
            try {
                this.Seguimiento.materia_id = this.selectMateria.id;
                this.Seguimiento.semestre_id = this.selectSemestre.id;
                this.Seguimiento.grupo_id = this.selectGrupo.id;
                this.Seguimiento.carrera_id = this.selectCarrera.id;
                this.Seguimiento.profesor_id = this.selectProfesor.id;
                await api.put(`/Registro/Seguimiento/${id}`, this.Seguimiento);

                this.consultar();
                this.$refs.modalSeguimiento.cerrar();

                this.Seguimiento = this.selectOptionNull();
                this.$swal.fire('Éxito', '✅ Registro actualizado correctamente', 'success');
                this.errores = {}; // 🔄 Limpia los errores
            } catch (error) {

                if (error.response && error.response.status === 422) {
                    this.errores = error.response.data.errors;
                } else {
                    console.error(error);
                    this.$swal.fire('Error', '❌ No se pudo agregar el registro', 'error');
                }

            }
        },
        validarAno() {
            const ano = this.Seguimiento.ano;
            const erroresAno = [];
            const soloNumeros = /^[0-9]*$/;
            if (!soloNumeros.test(ano.toString())) {
                this.Seguimiento.ano = this.Seguimiento.ano.replace(/\D/g, '');
                erroresAno.push('El año solo debe contener números.');
                // Elimina todo lo que no sea número
            }
            if (ano.toString().length > 4) {
                this.Seguimiento.ano = this.Seguimiento.ano.slice(0, 4);
                erroresAno.push('El año no debe exceder los 4 dígitos.');
            }
            this.errores.ano = erroresAno.length ? erroresAno : [];
        },
        selectOptionNull() {
            return {
                id: null,
                carrera_id: null,
                carreras: null,
                grupo_id: null,
                grupos: null,
                materia_id: null,
                materias: null,
                profesor_id: null,
                profesores: null,
                semestre_id: null,
                semestres: null,
                ano: null,
            };
        },
        selectProfesorOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoading = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Profesor`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsProfesor = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsProfesor = [];
                } finally {
                    this.selectLoading = false
                }
            }, 300)
        },
        // selectMateriaOptions
        selectMateriaOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingMateria = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Materia`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsMateria = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsMateria = [];
                } finally {
                    this.selectLoadingMateria = false
                }
            }, 300)
        },
        // selectSemestreOptions
        selectSemestreOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingSemestre = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Semestre`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsSemestre = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsSemestre = [];
                } finally {
                    this.selectLoadingSemestre = false
                }
            }, 300)
        },
        // selectGrupoOptions
        selectGrupoOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingGrupo = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Grupo`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsGrupo = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsGrupo = [];
                } finally {
                    this.selectLoadingGrupo = false
                }
            }, 300)
        },
        // selectCarreraOptions
        selectCarreraOptions(search) {
            clearTimeout(this.selectSearchTimeout)
            this.selectSearchTimeout = setTimeout(async () => {
                this.selectLoadingCarrera = true;
                try {
                    const response = await api.get(`/Registro/Seguimiento/Carrera`, {
                        params: { search }
                    });
                    // Agregar la opción deshabilitada al inicio del array
                    this.selectOptionsCarrera = [
                        {
                            id: null,
                            text: 'Seleccione una opción',
                            disabled: true
                        },
                        ...response.data.map(item => ({
                            id: item.id,
                            text: item.nombre
                        }))
                    ];
                } catch (error) {
                    console.error('Error al cargar opciones:', error)
                    this.selectOptionsCarrera = [];
                } finally {
                    this.selectLoadingCarrera = false
                }
            }, 300)
        },
        parcialesObjeto(parciales) {
            if (!Array.isArray(parciales)) return {};
            return parciales.map(item => {
                // Reemplaza espacios por "_" y asegura el formato correcto
                return typeof item === 'string'
                    ? item.replace(/\s+/g, '_')
                    : item;
            });
        },
        async formato1(id) {
            try {
                const response = await api.get(`/Registro/Seguimiento/${id}/formato1`);
                this.alumnos = response.data.alumnos;
                this.estadisticas = response.data.estadisticas;
                this.parciales = this.parcialesObjeto(response.data.parciales);

                const element = this.$refs.pdfContent;
                element.style.visibility = 'visible';
                element.style.position = 'static';
                element.style.left = '0';

                const options = {
                    margin: 0.5,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                await html2pdf().set(options).from(element).save();

                element.style.visibility = 'hidden';
                element.style.position = 'absolute';
                element.style.left = '-9999px';
            } catch (error) {
                console.error('Error al generar Formato 1:', error);
            }
        }

    },
    mounted() {
        this.consultar();
    }
}
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
    border: 1px solid black;
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

/* TEXTO VERTICAL (Para encabezados de tabla inferior) */
.vertical-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    white-space: nowrap;
    height: 100px;
    /* Altura forzada para el header */
    text-align: center;
    vertical-align: middle;
    display: block;
    margin: 0 auto;
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
    border: 1px solid black;
}

.nested-wrapper {
    padding: 0 !important;
    border: none !important;
    vertical-align: top;
}
</style>