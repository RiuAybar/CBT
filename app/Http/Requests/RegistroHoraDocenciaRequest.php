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
            'horasImpartidas' => 'required|integer|max:24|min:1',
            'carrera_id' => [
                'required',
                Rule::unique('registro_horas_docencias')
                    ->where(function ($query) {
                        return $query->where('mes', $this->mes);
                    })
                    ->ignore($Id),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'mes.required' => 'El mes es obligatorio.',
            'mes.max' => 'Excede el número maximo de caracteres.',
            
            'horasImpartidas.required' => 'Las horas son obligatorias.',
            'horasImpartidas.integer' => 'Solo se aceptan números enteros.',
            'horasImpartidas.max' => 'Máximo 24 horas.',
            'horasImpartidas.min' => 'Debe haber al menos 1 hora impartida.',

            'carrera_id.required' => 'La carrera es obligatoria.',
            'carrera_id.unique' => 'Ya existe un registro para esa carrera en el mes seleccionado.',
        ];
    }
}
