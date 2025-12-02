<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SeguimientoHorariosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Id que estamos actualizando (si existe)
        // $Id = $this->route('seguimientoHorario') ?? null;
        return [
            'dia' => [
                'required',
                'string',
                'max:20',
                'in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO,DOMINGO',
            ],

            'hora_inicio' => [
                'required',
                'date_format:H:i:s',
            ],

            'hora_fin' => [
                'required',
                'date_format:H:i:s',
                'after:hora_inicio',  //  <-- Validación importante
            ],

            'seguimiento_id' => [
                'required',
                'integer',
                'exists:seguimientos,id',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'dia.required'       => 'El día es obligatorio.',
            'dia.in'             => 'El día no es válido.',

            'hora_inicio.required'     => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format'  => 'La hora de inicio debe tener el formato HH:MM:SS.',

            'hora_fin.required'        => 'La hora de fin es obligatoria.',
            'hora_fin.date_format'     => 'La hora de fin debe tener el formato HH:MM:SS.',
            'hora_fin.after'           => 'La hora final debe ser mayor que la hora inicial.',

            'seguimiento_id.required'  => 'El ID del seguimiento es obligatorio.',
            'seguimiento_id.exists'    => 'El seguimiento seleccionado no existe.',
        ];
    }
}
