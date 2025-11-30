<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro Escolar</title>
    <style>
        /* CONFIGURACIÓN DE PÁGINA PARA DOMPDF */
        @page {
            margin: 0.5cm 0.5cm;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            font-size: 8px;
            /* Fuente base pequeña para que quepa todo */
            line-height: 1.1;
            color: #000;
        }

        /* UTILIDADES */
        .w-100 {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /* ESTILOS DE TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
            /* Crucial para mantener anchos fijos */
        }

        td,
        th {
            border: 0.5pt solid black;
            padding: 2px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* HEADER PRINCIPAL */
        .main-header {
            background-color: #f2f2f2;
            /* Gris claro como en la imagen? O blanco */
            font-size: 10px;
            border-bottom: double 2px black;
            /* Doble borde visual */
        }

        /* ETIQUETAS PEQUEÑAS (Ej: 1/ DIRECCIÓN) */
        .tiny-label {
            font-size: 5pt;
            color: #333;
            display: block;
            margin-bottom: 1px;
            text-align: left;
        }

        /* SECCIÓN DE DATOS (SUPERIOR) */
        .data-cell {
            vertical-align: top;
            padding: 0;
            border: none;
        }

        /* Anidación limpia para evitar dobles bordes */
        .nested-table {
            margin: -0.5pt;
            /* Solapamiento de bordes */
            width: calc(100% + 1pt);
        }

        /* TEXTO VERTICAL (TRUCO DOMPDF) */
        .vertical-wrapper {
            height: 90px;
            /* Altura de la celda contenedora */
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .vertical-text {
            display: block;
            transform: rotate(-90deg);
            transform-origin: center center;
            width: 90px;
            /* Ancho virtual al rotar */
            position: absolute;
            top: 40px;
            /* Ajustar a ojo según altura */
            left: 50%;
            margin-left: -50px;
            /* Centrar */
            text-align: center;
            white-space: nowrap;
        }

        /* Alturas específicas de filas */
        .row-h-30 {
            height: 30px;
        }

        .row-h-15 {
            height: 15px;
        }

        /* Colores de fondo */
        .bg-gray {
            background-color: #f9f9f9;
        }

        /* Ajuste fino de columnas de alumnos */
        .col-no {
            width: 20px;
        }

        .col-sex {
            width: 20px;
        }

        .col-name {
            width: auto;
        }

        .col-grade {
            width: 25px;
        }

        .col-obs {
            width: 100px;
        }
    </style>
</head>

<body>

    <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 10%; border: none; text-align: left; vertical-align: middle;">
                <img src="{{ public_path('Img/Imagen2.png') }}" style="width: 60px; height: auto; display: block;">
            </td>

            <td style="width: 88%; border: none; text-align: left; vertical-align: middle; padding-left: 5px;">
                <div style="font-weight: bold; font-size: 11pt; color: #000; line-height: 1.2;">
                    GOBIERNO DEL ESTADO DE MÉXICO
                </div>
                <div style="font-weight: bold; font-size: 10pt; color: #000; line-height: 1.2;">
                    SECRETARÍA DE EDUCACIÓN
                </div>
                <div style="font-weight: bold; font-size: 9pt; color: #000; line-height: 1.2;">
                    SUBSECRETARÍA DE EDUCACIÓN MEDIA SUPERIOR Y SUPERIOR
                </div>
            </td>
        </tr>
    </table>
    <div class="text-center bold"
        style="font-size: 11px; padding: 5px; border: 0.5pt solid black; border-bottom: none;">
        REGISTRO DE FALTAS DE ASISTENCIA , CALIFICACIONES Y PROMEDIO POR ASIGNATURA
    </div>

    <table style="border: none;">
        <tr>
            <td style="width: 60%; padding: 0; vertical-align: top;">
                <table class="nested-table">
                    <tr>
                        <td colspan="4">
                            <span class="tiny-label">1/ DIRECCIÓN</span>
                            <div class="text-center bold">GENERAL DE EDUCACIÓN MEDIA SUPERIOR</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <span class="tiny-label">2/ DEPARTAMENTO</span>
                            <div class="text-center">DE BACHILLERATO TECNOLÓGICO</div>
                        </td>
                    </tr>
                    <tr class="row-h-30">
                        <td colspan="4">
                            <span class="tiny-label">3/ NOMBRE DE LA ESCUELA</span>
                            <div class="text-center bold" style="font-size: 11px;">CBT, AMANALCO DE BECERRA</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="tiny-label">4/ TURNO</span>
                            <div class="text-center bold">DISCONTINUO</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">5/ NIVEL</span>
                            <div class="text-center bold">MEDIO SUPERIOR TÉCNICO</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="tiny-label">8/ CLAVE CENTRO DE TRABAJO</span>
                            <div class="text-center bold">15ECT0112M</div>
                        </td>
                        <td>
                            <span class="tiny-label">9/ No. DE C.R.E.S.E.</span>
                            <div class="text-center">NA</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">10/ ZONA ESCOLAR</span>
                            <div class="text-center bold text-right" style="padding-right: 10px;">BT009</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="tiny-label">11/ DOMICILIO</span>
                            <div class="bold text-center">DOMICILIO CONOCIDO S/N</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">12/ LOCALIDAD O COLONIA</span>
                            <div class="bold text-center">EL POTRERO</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="tiny-label">13/ MUNICIPIO</span>
                            <div class="bold text-center">AMANALCO</div>
                        </td>
                        <td>
                            <span class="tiny-label">14/ TELÉFONO</span>
                            <div class="bold text-center">7228353322</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">15/ CICLO ESCOLAR</span>
                            <div class="bold text-center">2024-2025</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="tiny-label">16/ NOMBRE DEL PROFESOR</span>
                            <div class="bold text-center">LÓPEZ SANTANA SEBASTIAN</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">17/ TELÉFONO</span>
                            <div class="bold text-center">7228353322</div>
                        </td>
                    </tr>
                    <tr class="row-h-30">
                        <td colspan="2">
                            <span class="tiny-label">18/ DOMICILIO</span>
                            <div class="bold text-left">San Lucas, Amanalco;Méx</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">19/ LOCALIDAD O COLONIA</span>
                            <div class="bold text-center" style="margin-top: 5px;">San Lucas 2da secc.</div>
                        </td>
                    </tr>
                    <tr class="row-h-30">
                        <td colspan="2">
                            <span class="tiny-label">20/ ASIGNATURA</span>
                            <div class="bold" style="font-size: 8px;">SUBMÓDULO II. DISEÑA Y ADMINISTRA BASES DE DATOS
                                SIMPLES</div>
                        </td>
                        <td>
                            <span class="tiny-label">21/ CURSO</span>
                            <div class="text-center" style="margin-top: 5px;">ORDINARIO</div>
                        </td>
                        <td>
                            <span class="tiny-label">22/ GRUPO</span>
                            <div class="text-center bold" style="margin-top: 5px;">2 1</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid black;">
                            <span class="tiny-label">23/ BACHILLERATO O CARRERA</span>
                            <div class="text-center bold">TÉCNICO EN INFORMÁTICA</div>
                        </td>
                        <td style="border: 1px solid black;">
                            <span class="tiny-label">24/ SEMESTRE</span>
                            <div class="text-center bold">2</div>
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width: 40%; padding: 0; vertical-align: top; border: none;">
                <table class="nested-table">
                    <tr>
                        <td style="width: 50%;" class="text-center"><span class="tiny-label">33/</span> MESES</td>
                        <td style="width: 50%;" class="text-center"><span class="tiny-label">34/</span> No. DE HORAS
                            IMPARTIDAS</td>
                    </tr>
                    <tr>
                        <td class="text-center">FEBRERO</td>
                        <td class="text-center">20</td>
                    </tr>
                    <tr>
                        <td class="text-center">MARZO</td>
                        <td class="text-center">24</td>
                    </tr>
                    <tr>
                        <td class="text-center">ABRIL</td>
                        <td class="text-center">15</td>
                    </tr>
                    <tr>
                        <td class="text-center">MAYO</td>
                        <td class="text-center">24</td>
                    </tr>
                    <tr>
                        <td class="text-center">JUNIO</td>
                        <td class="text-center">21</td>
                    </tr>
                    <tr>
                        <td class="text-center">JULIO</td>
                        <td class="text-center">12</td>
                    </tr>
                    <tr>
                        <td class="bold text-center">TOTAL DE HORAS SEMESTRALES</td>
                        <td class="bold text-center">116</td>
                    </tr>
                </table>

                <table class="nested-table" style="margin-top: -0.5pt;">
                    <tr>
                        <td class="bold text-center" style="width: 50%;">
                            <span class="tiny-label" style="text-align: left;">35/</span> DATOS ESTADÍSTICOS
                        </td>
                        <td class="bold text-center">HOMBRES</td>
                        <td class="bold text-center">MUJERES</td>
                        <td class="bold text-center">TOTAL</td>
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
                        <td class="text-center">34</td>
                        <td class="text-center">52</td>
                        <td class="text-center">86</td>
                    </tr>
                    <tr>
                        <td>PROMEDIO DE CALIFICACIONES</td>
                        <td class="text-center">3.40</td>
                        <td class="text-center">4.00</td>
                        <td class="text-center">3.74</td>
                    </tr>
                    <tr>
                        <td>METAS INSTITUCIONALES LOGRADAS</td>
                        <td colspan="3" class="text-center">NO</td>
                    </tr>
                </table>

                <table class="nested-table" style="margin-top: -0.5pt;">
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;">
                            <span class="tiny-label">38/ HORARIO</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; vertical-align: top; border-right: none;">LUNES</td>
                        <td style="width: 50%; vertical-align: top; border-left: none;">JUEVES</td>
                    </tr>
                    <tr class="row-h-30">
                        <td style="vertical-align: top; border-right: none;">
                            MARTES <span style="margin-left: 5px;">7:30-9:10, 12:00-12:50</span>
                        </td>
                        <td style="vertical-align: top; border-left: none;"></td>
                    </tr>
                    <tr>
                        <td style="border-bottom: none; border-right: none;">MIERCOLES</td>
                        <td style="border-bottom: none; border-left: none;">
                            VIERNES <span style="margin-left: 5px;">12:00-14:30</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="margin-top: -0.5pt;">
        <thead>
            <tr>
                <th class="col-no" rowspan="2">
                    <div class="vertical-wrapper">
                        <div class="vertical-text">No. DE LISTA</div>
                    </div>
                </th>
                <th class="col-sex" rowspan="2">
                    <div class="vertical-wrapper">
                        <div class="vertical-text">SEXO</div>
                    </div>
                </th>
                <th class="col-name text-center" rowspan="2">
                    NOMBRE DEL ALUMNO (A)
                </th>

                <th colspan="3" style="padding: 20px; width: 75px;">
                    <div class="vertical-wrapper" style="height: 60px; height: 150px">
                        <div class="vertical-text" style="top: 0px;">
                            FALTAS
                            <br>
                            DE
                            <br>
                            ASISTENCIA
                        </div>
                    </div>
                </th>

                <th class="col-grade" rowspan="2">
                    <div class="vertical-wrapper">
                        <div class="vertical-text">TOT. DE FALTAS</div>
                    </div>
                </th>

                <th class="col-grade" rowspan="2">
                    <div class="vertical-wrapper">
                        <div class="vertical-text">% DE INASISTENCIA</div>
                    </div>
                </th>

                <th colspan="3" style="padding: 0; width: 75px;">
                    <div class="vertical-wrapper" style="height: 60px;">
                        <div class="vertical-text" style="top: 25px;">EVALUACIONES</div>
                    </div>
                </th>

                <th class="col-grade" rowspan="2">
                    <div class="vertical-wrapper">
                        <div class="vertical-text">SUMA</div>
                    </div>
                </th>
                <th class="col-grade" rowspan="2">
                    <div class="vertical-wrapper">
                        <div class="vertical-text">PROMEDIO</div>
                    </div>
                </th>
                <th class="col-obs text-center" rowspan="2">
                    OBSERVACIONES
                </th>
            </tr>
            <tr>
                <td class="text-center bold">1a</td>
                <td class="text-center bold">2a</td>
                <td class="text-center bold">3a</td>

                <td class="text-center bold">1a</td>
                <td class="text-center bold">2a</td>
                <td class="text-center bold">3a</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">M</td>
                <td style="padding-left: 5px;">ARRIAGA ARRIAGA CHRISTOPHER</td>

                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td class="text-center">0.0</td>

                <td class="text-center">8</td>
                <td class="text-center">7</td>
                <td class="text-center">7</td>
                <td class="text-center">22.0</td>
                <td class="text-center">7.0</td>

                <td></td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td class="text-center">M</td>
                <td style="padding-left: 5px;">ALVAREZ COLIN LUIS ENRIQUE</td>

                <td class="text-center">5</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center">5</td>
                <td class="text-center">4.3</td>

                <td class="text-center">5</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center">5.0</td>
                <td class="text-center">5.0</td>

                <td class="text-center">E. EXTR.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; width: 100%; page-break-inside: avoid;">

        <div style="text-align: right; font-size: 8px; margin-bottom: 15px;">
            El Potrero, Amanalco, México viernes, 28 de noviembre de 2025
        </div>

        <div style="text-align: center; font-weight: bold; font-size: 9px; margin-bottom: 10px;">
            FIRMAS
        </div>

        <table style="width: 100%; border: none; border-collapse: collapse;">

            <tr>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">LÓPEZ SANTANA SEBASTIAN</div>
                    <div style="font-size: 7px;">DOCENTE</div>
                </td>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">ANDREA GUADALUPE HERNÁNDEZ HERNÁNDEZ</div>
                    <div style="font-size: 7px;">ORIENTADOR</div>
                </td>
            </tr>

            <tr>
                <td style="border: none; text-align: center; padding-top: 15px; font-weight: bold; font-size: 8px;">
                    REVISÓ
                </td>
                <td style="border: none; text-align: center; padding-top: 15px; font-weight: bold; font-size: 8px;">
                    AUTORIZÓ
                </td>
            </tr>

            <tr>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">ARÍA DEL CARMEN ASSENETH VELÁZQUEZ LÓP</div>
                    <div class="bold" style="font-size: 7px;">SUBDIRECTORA ESCOLAR</div>
                </td>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">GADIEL RECILLAS MIRANDA</div>
                    <div class="bold" style="font-size: 7px;">DIRECTOR ESCOLAR</div>
                </td>
            </tr>

            <tr>
                <td colspan="2"
                    style="border: none; text-align: center; padding-top: 15px; font-weight: bold; font-size: 8px;">
                    VALIDACIÓN PARA CAPTURA EN SISTEMA
                </td>
            </tr>

            <tr>
                <td colspan="2" style="border: none; text-align: center; vertical-align: bottom; height: 50px;">
                    <div style="border-bottom: 1px solid black; width: 40%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">LUIS GONZÁLEZ CALIXTO</div>
                    <div class="bold" style="font-size: 7px;">SECRETARIO ESCOLAR</div>
                </td>
            </tr>

        </table>
    </div>

</body>

</html>
