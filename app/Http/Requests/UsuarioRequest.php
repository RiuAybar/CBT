<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
        $id = $this->route('user') ?? null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'estatus' => 'required|in:habilitado,deshabilitado',
            'password' => $id
                ? 'nullable|string|min:8|confirmed'
                : 'nullable|string|min:8|confirmed',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'El campo es obligatorio.',
            'name.max' => 'Excede el número máximo de caracteres.',

            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debe ser un correo válido.',
            'email.unique' => 'El correo ya está en uso.',

            'estatus.required' => 'El estatus es obligatorio.',
            'estatus.in' => 'El estatus debe ser Habilitado o Deshabilitado.',

            // 'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'El campo de contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
