<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GrupoRequest extends FormRequest
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
        $Id = $this->route('Grupo') ?? null;
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('grupos')
                    ->where(function ($query) {
                        return $query->where('grado_id', $this->grado_id);
                    })
                    ->ignore($Id),
            ],
            'grado_id' => [
                'required',
                'integer',
                'exists:grados,id'
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El grupo es obligatorio.',
            'nombre.max' => 'Excede el número maximo de caracteres.',
            'nombre.unique' => 'Ya existe un grupo con ese nombre en el grado seleccionado.',
            'Grado_id.required' => 'El grado es obligatorio.',
            'Grado_id.integer' => 'El grado debe ser un número entero.',
            'Grado_id.exists' => 'El grado seleccionado no es válido.',
        ];
    }
}
