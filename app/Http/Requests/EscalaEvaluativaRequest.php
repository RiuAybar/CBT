<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EscalaEvaluativaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtiene el ID al editar (del parámetro de la ruta)
        $id = $this->route('EscalaEvaluativa')?->id;

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('escalas_evaluativas', 'nombre')->ignore($id),
            ],
            'abreviatura' => [
                'required',
                'string',
                'max:10',
                Rule::unique('escalas_evaluativas', 'abreviatura')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Este nombre ya está registrado.',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres.',

            'abreviatura.required' => 'La abreviatura es obligatoria.',
            'abreviatura.unique' => 'Esta abreviatura ya existe.',
            'abreviatura.max' => 'La abreviatura no puede exceder 10 caracteres.',
        ];
    }
}
