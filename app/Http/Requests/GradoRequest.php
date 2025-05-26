<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GradoRequest extends FormRequest
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
        $Id = $this->route('Grado') ?? null;
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                // Validación única para creación o actualización
                Rule::unique('Grados')->ignore($Id),
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El grado es obligatorio.',
            'nombre.max' => 'Excede el número maximo de caracteres.',
            'nombre.unique' => 'El grado ya está registrado.',
        ];
    }
}
