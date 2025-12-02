<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegistroHoraDocenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Id que estamos actualizando (si existe)
        $Id = $this->route('RegistroHorasDocencia') ?? null;
        return [
            'mes' => [
                'required',
                'string',
                'max:100',
            ],
            'horasImpartidas' => [
                'required',
                'integer',
                'min:1',
            ],
            'carrera_id' => [
                'required',
                Rule::unique('registro_horas_docencias')
                    ->where(fn($q) =>
                        $q->where('mes', $this->mes)
                          ->where('materia_id', $this->materia_id)
                    )
                    ->ignore($Id),
            ],

            'materia_id' => [
                'required',
                Rule::unique('registro_horas_docencias')
                    ->where(fn($q) =>
                        $q->where('mes', $this->mes)
                          ->where('carrera_id', $this->carrera_id)
                    )
                    ->ignore($Id),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            // mes
            'mes.required' => 'El mes es obligatorio.',
            'mes.string'   => 'El mes debe ser un texto válido.',
            'mes.max'      => 'El mes excede el número máximo de caracteres.',

            // horasImpartidas
            'horasImpartidas.required' => 'Las horas impartidas son obligatorias.',
            'horasImpartidas.integer'  => 'Las horas impartidas deben ser un número entero.',
            'horasImpartidas.min'      => 'Debe ingresar al menos 1 hora.',
            
            // carrera_id
            'carrera_id.required' => 'La carrera es obligatoria.',
            'carrera_id.unique'   => 'Ya existe un registro con esta carrera, materia y mes.',

            // materia_id
            'materia_id.required' => 'La materia es obligatoria.',
            'materia_id.unique'   => 'Ya existe un registro con esta materia, carrera y mes.',
        ];
    }
}
