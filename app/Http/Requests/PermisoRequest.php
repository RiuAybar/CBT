<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermisoRequest extends FormRequest
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
        $Id = $this->route('Permission') ?? null;
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                // Validación única para creación o actualización
                Rule::unique('Permissions')->ignore($Id),
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'El permiso es obligatorio.',
            'name.max' => 'Excede el número maximo de caracteres.',
            'name.unique' => 'El permiso ya está registrado.',
        ];
    }
}
