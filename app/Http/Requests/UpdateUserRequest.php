<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                // validar que el email sea único excepto para el usuario actual
                Rule::unique('users')->ignore($this->user()->id),
            ],

            'telefono' => 'required|string|max:10|regex:/^[0-9]+$/',
            'domicilio' => 'required|string|max:100',
            'localidadColonia' => 'required|string|max:100',

            'password_hold' => ['required'],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'Excede el número maximo de caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'El correo electrónico ya está en uso por otro usuario.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no debe tener más de 10 caracteres.',
            'telefono.regex' => 'El teléfono solo debe contener números.',

            'domicilio.required' => 'El domicilio es obligatorio.',
            'domicilio.max' => 'El domicilio no debe exceder los 100 caracteres.',

            'localidadColonia.required' => 'La colonia/localidad es obligatoria.',
            'localidadColonia.max' => 'La colonia/localidad no debe exceder los 100 caracteres.',
            'password_hold.required' => 'La contraseña es obligatorio.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];
    }
}
