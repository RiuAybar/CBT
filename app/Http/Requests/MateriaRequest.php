<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Nette\Utils\Arrays;

class MateriaRequest extends FormRequest
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
        $id = $this->route('Materia') ?? null;
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('Materias')->ignore($id),
            ]
        ];
    }
    public function messages(): Array
    {
        return [
            'nombre.required' => 'El campo es obligatorio.',
            'nombre.max' => 'Excede el número maximo de caracteres.',
            'nombre.unique' => 'La materia ya está registrado.',
        ];
    }
}
