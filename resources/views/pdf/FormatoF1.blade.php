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
            width: 100px;
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
                            <div class="text-center bold">{{ $Escuela?->direccion }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <span class="tiny-label">2/ DEPARTAMENTO</span>
                            <div class="text-center">{{ $Escuela?->departamento }}</div>
                        </td>
                    </tr>
                    <tr class="row-h-30">
                        <td colspan="4">
                            <span class="tiny-label">3/ NOMBRE DE LA ESCUELA</span>
                            <div class="text-center bold" style="font-size: 11px;">{{ $Escuela?->nombre_escuela }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="tiny-label">4/ TURNO</span>
                            <div class="text-center bold">{{ $Escuela?->turno }}</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">5/ NIVEL</span>
                            <div class="text-center bold">{{ $Escuela?->nivel }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="tiny-label">8/ CLAVE CENTRO DE TRABAJO</span>
                            <div class="text-center bold">{{ $Escuela?->clave_trabajo }}</div>
                        </td>
                        <td>
                            <span class="tiny-label">9/ No. DE C.R.E.S.E.</span>
                            <div class="text-center">{{ $Escuela?->numero_cct }}</div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">10/ ZONA ESCOLAR</span>
                            <div class="text-center bold text-right" style="padding-right: 10px;">
                                {{ $Escuela?->zona_escolar }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="tiny-label">11/ DOMICILIO</span>
                            <div class="bold text-center">
                                {{ $Escuela?->domicilio }}
                            </div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">12/ LOCALIDAD O COLONIA</span>
                            <div class="bold text-center">
                                {{ $Escuela?->localidad_colonia }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="tiny-label">13/ MUNICIPIO</span>
                            <div class="bold text-center">
                                {{ $Escuela?->municipio }}
                            </div>
                        </td>
                        <td>
                            <span class="tiny-label">14/ TELÉFONO</span>
                            <div class="bold text-center">
                                {{ $Escuela?->telefono }}
                            </div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">15/ CICLO ESCOLAR</span>
                            <div class="bold text-center">
                                {{ $Seguimiento?->ciclo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="tiny-label">16/ NOMBRE DEL PROFESOR</span>
                            <div class="bold text-center">
                                {{ $Seguimiento?->Profesor?->name }}
                            </div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">17/ TELÉFONO</span>
                            <div class="bold text-center">
                                {{ $Seguimiento?->Profesor?->telefono }}
                            </div>
                        </td>
                    </tr>
                    <tr class="row-h-30">
                        <td colspan="2">
                            <span class="tiny-label">18/ DOMICILIO</span>
                            <div class="bold text-left">
                                {{ $Seguimiento?->Profesor?->domicilio }}
                            </div>
                        </td>
                        <td colspan="2">
                            <span class="tiny-label">19/ LOCALIDAD O COLONIA</span>
                            <div class="bold text-center" style="margin-top: 5px;">
                                {{ $Seguimiento?->Profesor?->localidadColonia }}
                            </div>
                        </td>
                    </tr>
                    <tr class="row-h-30">
                        <td colspan="2">
                            <span class="tiny-label">20/ ASIGNATURA</span>
                            <div class="bold" style="font-size: 8px;">
                                {{ $Seguimiento?->materia?->nombre }}
                            </div>
                        </td>
                        <td>
                            <span class="tiny-label">21/ CURSO</span>
                            <div class="text-center" style="margin-top: 5px;">ORDINARIO</div>
                        </td>
                        <td>
                            <span class="tiny-label">22/ GRUPO</span>
                            <div class="text-center bold" style="margin-top: 5px;">
                                {{ $Seguimiento?->Grupos?->grado?->nombre }}
                                {{ $Seguimiento?->Grupos?->nombre }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid black;">
                            <span class="tiny-label">23/ BACHILLERATO O CARRERA</span>
                            <div class="text-center bold">
                                {{ $Seguimiento?->Carrera?->nombre }}
                            </div>
                        </td>
                        <td style="border: 1px solid black;">
                            <span class="tiny-label">24/ SEMESTRE</span>
                            <div class="text-center bold">
                                {{ $Seguimiento?->Semestre?->nombre }}
                            </div>
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
                    @foreach ($RegistroHorasDocencia as $registro)
                        <tr>
                            <td class="text-center">{{ $registro->mes }}</td>
                            <td class="text-center">{{ $registro->horasImpartidas }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="bold text-center">TOTAL DE HORAS SEMESTRALES</td>
                        <td class="bold text-center">{{ $RegistroHorasDocencia->pluck('horasImpartidas')->sum() }}</td>
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
                        <td class="text-center">{{ $conteoSexo['M'] ?? 0 }}</td>
                        <td class="text-center">{{ $conteoSexo['F'] ?? 0 }}</td>
                        <td class="text-center">{{ $alumnos->count() }}</td>
                    </tr>
                    <tr>
                        <td>BAJAS DURANTE EL AÑO</td>
                        <td class="text-center">
                            {{ isset($BajasAltasXs['M']['Baja']) ? $BajasAltasXs['M']['Baja'] : 0 }}
                        </td>
                        <td class="text-center">
                            {{ isset($BajasAltasXs['F']['Baja']) ? $BajasAltasXs['F']['Baja'] : 0 }}

                        </td>
                        <td class="text-center">
                            {{ ($BajasAltasXs['M']['Baja'] ?? 0) + ($BajasAltasXs['F']['Baja'] ?? 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td>EXISTENCIA AL FINAL DEL AÑO</td>
                        <td class="text-center">{{ ($conteoSexo['M'] ?? 0) - ($BajasAltasXs['M']['Baja'] ?? 0) }}</td>
                        <td class="text-center">{{ ($conteoSexo['F'] ?? 0) - ($BajasAltasXs['F']['Baja'] ?? 0) }}</td>
                        <td class="text-center">
                            {{ ($conteoSexo['M'] ?? 0) -
                                ($BajasAltasXs['M']['Baja'] ?? 0) +
                                ($conteoSexo['F'] ?? 0) -
                                ($BajasAltasXs['F']['Baja'] ?? 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td>No. DE APROBADOS</td>
                        <td class="text-center">{{ $Aprobados['M'] ?? 0 }}</td>
                        <td class="text-center">{{ $Aprobados['F'] ?? 0 }}</td>
                        <td class="text-center">{{ ($Aprobados['F'] ?? 0) + ($Aprobados['M'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>No. REPROBADOS</td>
                        <td class="text-center">{{ $Reprobados['M'] ?? 0 }}</td>
                        <td class="text-center">{{ $Reprobados['F'] ?? 0 }}</td>
                        <td class="text-center">{{ ($Reprobados['F'] ?? 0) + ($Reprobados['M'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>% DE ALUMNOS APROBADOS</td>
                        <td class="text-center">
                            {{ ($conteoSexo['M'] ?? 0) > 0 ? number_format((($Aprobados['M'] ?? 0) / $conteoSexo['M']) * 100, 2) : 0 }}
                        </td>
                        <td class="text-center">
                            {{ ($conteoSexo['F'] ?? 0) > 0 ? number_format((($Aprobados['F'] ?? 0) / $conteoSexo['F']) * 100, 2) : 0 }}
                        </td>
                        <td class="text-center">
                            {{ $alumnos->count() > 0 ? number_format(((($Aprobados['M'] ?? 0) + ($Aprobados['F'] ?? 0)) / $alumnos->count()) * 100, 2) : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>% DE ALUMNOS REPROBADOS</td>
                        <td class="text-center">
                            {{ ($conteoSexo['M'] ?? 0) > 0 ? number_format(100 - (($Aprobados['M'] ?? 0) / $conteoSexo['M']) * 100, 2) : 0 }}
                        </td>
                        <td class="text-center">
                            {{ ($conteoSexo['F'] ?? 0) > 0 ? number_format(100 - (($Aprobados['F'] ?? 0) / $conteoSexo['F']) * 100, 2) : 0 }}
                        </td>
                        <td class="text-center">
                            {{ $alumnos->count() > 0 && ($conteoSexo['F'] ?? 0) && ($conteoSexo['M'] ?? 0) ? number_format(((100 - (($Aprobados['M'] ?? 0) / $conteoSexo['M']) * 100 + (100 - (($Aprobados['F'] ?? 0) / $conteoSexo['F']) * 100)) / $alumnos->count()) * 100, 2) : 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td>SUMA DE CALIFICACIONES</td>
                        <td class="text-center">
                            {{ $sumaPorSexo['M'] ?? 0 }}
                        </td>
                        <td class="text-center">
                            {{ $sumaPorSexo['F'] ?? 0 }}
                        </td>
                        <td class="text-center">
                            {{ ($sumaPorSexo['M'] ?? 0) + ($sumaPorSexo['F'] ?? 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td>PROMEDIO DE CALIFICACIONES</td>
                        <td class="text-center">
                            {{ ($conteoSexo['M'] ?? 0) > 0 ? number_format(($sumaPorSexo['M'] ?? 0) / $conteoSexo['M'], 2) : 0 }}
                        </td>
                        <td class="text-center">
                            {{ ($conteoSexo['F'] ?? 0) && ($sumaPorSexo['F'] ?? 0) ? ($sumaPorSexo['F'] ?? 0) / ($conteoSexo['F'] ?? 0) : 0 }}
                        </td>
                        <td class="text-center">
                            @php
                                $sumaM = $sumaPorSexo['M'] ?? 0;
                                $sumaF = $sumaPorSexo['F'] ?? 0;
                                $conteoM = $conteoSexo['M'] ?? 0;
                                $conteoF = $conteoSexo['F'] ?? 0;

                                $division = $conteoF > 0 ? $sumaF / $conteoF : 0;

                                $resultado = $sumaM + $division + $conteoM;
                            @endphp
                            {{ number_format($resultado, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>METAS INSTITUCIONALES LOGRADAS</td>
                        <td colspan="3" class="text-center">
                            @php
                                $aprobM = $Aprobados['M'] ?? 0;
                                $aprobF = $Aprobados['F'] ?? 0;

                                $totalAlumnos = $alumnos->count();

                                // Evitar división entre 0 en ambos sexos
                                $porcM = $conteoM > 0 ? ($aprobM / $conteoM) * 100 : 0;
                                $porcF = $conteoF > 0 ? ($aprobF / $conteoF) * 100 : 0;

                                // Fórmula original pero segura
                                $resultadoPorc =
                                    $totalAlumnos > 0 ? ((100 - $porcM + (100 - $porcF)) / $totalAlumnos) * 100 : 0;

                                // Suma segura
                                $sumaGeneral = $sumaM + $sumaF / ($conteoF > 0 ? $conteoF : 1) + $conteoM;

                                // Regla final
                                if ($resultadoPorc == 0) {
                                    $resultadoFinal = $sumaGeneral >= 8.7 && $resultadoPorc == 0 ? 'Si' : 'No';
                                } else {
                                    $resultadoFinal = $sumaGeneral >= 8.7 && $resultadoPorc >= 90 ? 'Si' : 'No';
                                }
                            @endphp
                            {{ $resultadoFinal }}
                        </td>
                    </tr>
                </table>

                <table class="nested-table" style="margin-top: -0.5pt;">
                    <tr>
                        <td colspan="2" style="border-top: none; border-bottom: none;">
                            <span class="tiny-label">38/ HORARIO</span>
                        </td>
                    </tr>
                    @foreach ($SeguimientoHorario->chunk(2) as $fila)
                        <tr>
                            @foreach ($fila as $Seg)
                                <td>
                                    {{ $Seg->dia }}
                                    <span style="margin-left: 5px;">
                                        {{ $Seg->hora_inicio }} - {{ $Seg->hora_fin }}
                                    </span>
                                </td>
                            @endforeach

                            {{-- Si solo viene 1 registro, agregamos un <td> vacío para completar los 2 --}}
                            @if ($fila->count() == 1)
                                <td style="border-bottom: none;"></td>
                            @endif
                        </tr>
                    @endforeach

                    @if (!$SeguimientoHorario->count())
                        <tr>
                        <td style="width: 50%; vertical-align: top; border-right: none;">ㅤㅤ</td>
                        <td style="width: 50%; vertical-align: top; border-left: none;">ㅤㅤ</td>
                    </tr>
                    <tr class="row-h-30">
                        <td style="vertical-align: top; border-right: none;">ㅤㅤ
                            <span style="margin-left: 5px;">ㅤㅤ</span>
                        </td>
                        <td style="vertical-align: top; border-left: none;">ㅤㅤ</td>
                    </tr>
                    @endif
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
                    <div class="vertical-wrapper" style="height: 60px;">
                        <div class="vertical-text" style="top: 20px;">
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
            @foreach ($alumnos as $al)
                <tr>
                    <td class="text-center">
                        {{ $al->No_DE_LISTA }}
                    </td>
                    <td class="text-center">
                        {{ $al->sexo }}
                    </td>
                    <td style="padding-left: 5px;">
                        {{ $al->NOMBRE_DEL_ALUMNO }}
                    </td>

                    <td class="text-center">
                        {{ $al->faltas_Parcial_1 }}
                    </td>
                    <td class="text-center">
                        {{ $al->faltas_Parcial_2 }}
                    </td>
                    <td class="text-center">
                        {{ $al->faltas_Parcial_3 }}
                    </td>
                    <td class="text-center">
                        {{ $al->FALTAS }}
                    </td>
                    <td class="text-center">
                        @php
                            $totalHoras = $RegistroHorasDocencia->pluck('horasImpartidas')->sum();
                        @endphp
                        {{ $totalHoras > 0 ? number_format((100 / $totalHoras) * ($al->FALTAS ?? 0), 2) : 0 }}
                    </td>
                    <td class="text-center">
                        {{ $al->eval_Parcial_1 }}
                    </td>
                    <td class="text-center">
                        {{ $al->eval_Parcial_2 }}
                    </td>
                    <td class="text-center">
                        {{ $al->eval_Parcial_3 }}
                    </td>
                    <td class="text-center">
                        {{ $al->eval_Parcial_1 + $al->eval_Parcial_2 + $al->eval_Parcial_3 }}
                    </td>

                    @php
                        $vals = [$al->eval_Parcial_1, $al->eval_Parcial_2, $al->eval_Parcial_3];

                        // Filtrar valores válidos (que no sean null ni 0)
                        $validos = array_filter($vals, function ($v) {
                            return $v !== null && $v != 0;
                        });

                        // Si no hay valores válidos → promedio 0
                        $promedio = count($validos) > 0 ? array_sum($validos) / count($validos) : 0;
                    @endphp

                    <td class="text-center">
                        {{ $promedio < 6 ? '5' : number_format($promedio, 2) }}
                    </td>

                    @php
                        // 2. Contar cuántos valores iguales a 5 hay entre N36, O36, P36
                        $contar5 = 0;

                        foreach ($vals as $v) {
                            if ($v == 5) {
                                $contar5++;
                            }
                        }
                    @endphp
                    <td class="text-center">
                        {{ $al->estatus == 'Alta' ? ($promedio === '' || $promedio === null
                            ? ''
                            : ($contar5 >= 2
                                ? 'E. EXTR.'
                                : ($promedio < 6
                                    ? 'E. EXTR.'
                                    : ($totalHoras > 0 && number_format((100 / $totalHoras) * ($al->FALTAS ?? 0), 2) >= 20
                                        ? 'E. EXTR.'
                                        : '')))) : 'Baja' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; width: 100%; page-break-inside: avoid;">

        <div style="text-align: right; font-size: 8px; margin-bottom: 15px;">
            @php
                date_default_timezone_set('America/Mexico_City');

                $fecha = new DateTime(); // ← fecha actual (puedes poner otra)

                // Formateador en español (México)
                $fmt = new IntlDateFormatter(
                    'es_MX',
                    IntlDateFormatter::NONE,
                    IntlDateFormatter::NONE,
                    'America/Mexico_City',
                    IntlDateFormatter::GREGORIAN,
                    "EEEE, d 'de' MMMM 'de' y",
                );

                $fechaFormateada = mb_strtolower($fmt->format($fecha), 'UTF-8');
            @endphp
            El Potrero, Amanalco, México {{ $fechaFormateada }}
        </div>

        <div style="text-align: center; font-weight: bold; font-size: 9px; margin-bottom: 10px;">
            FIRMAS
        </div>

        <table style="width: 100%; border: none; border-collapse: collapse;">

            <tr>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">
                        {{ $Escuela?->docente }}
                    </div>
                    <div style="font-size: 7px;">DOCENTE</div>
                </td>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">
                        {{ $Seguimiento?->Orientador?->name }}
                    </div>
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
                    <div class="bold">
                        {{ $Escuela?->subdirector_escolar }}
                    </div>
                    <div class="bold" style="font-size: 7px;">SUBDIRECTORA ESCOLAR</div>
                </td>
                <td
                    style="width: 50%; border: none; text-align: center; vertical-align: bottom; height: 50px; padding: 0 10px;">
                    <div style="border-bottom: 1px solid black; width: 80%; margin: 0 auto 2px auto;"></div>
                    <div class="bold">
                        {{ $Escuela?->director_escolar }}
                    </div>
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
                    <div class="bold">
                        {{ $Escuela?->secretario_escolar }}
                    </div>
                    <div class="bold" style="font-size: 7px;">SECRETARIO ESCOLAR</div>
                </td>
            </tr>

        </table>
    </div>

</body>

</html>
