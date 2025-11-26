<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
        $id = $this->route('user');
        $id = is_object($id) ? $id->id : $id;
        $user = auth('api')->user();

        // Mapa Permiso → Nombre del Rol
        $restricciones = [
            'Puede agregar estudiantes' => 'estudiante',
            'Puede agregar profesores' => 'profesor',
            'Puede agregar orientadores' => 'orientador',
            'Puede agregar administradores' => 'administrador',
        ];

        if ($this->route('user')) {
            $restricciones = [
                'puede editar estudiantes' => 'estudiante',
                'puede editar profesores' => 'profesor',
                'puede editar orientadores' => 'orientador',
                'puede editar administradores' => 'administrador',
            ];
        }

        // Filtrar roles permitidos según permisos del usuario
        $rolesPermitidos = \Spatie\Permission\Models\Role::query()
            ->whereNotIn('name', array_filter(array_map(function ($permiso, $rol) use ($user) {
                return !$user->can($permiso) ? $rol : null;
            }, array_keys($restricciones), $restricciones)))
            ->pluck('id')
            ->toArray();

        return [
            'name' => 'required|string|max:255',
            'sexo' => 'required|in:M,F',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],

            // 'RolId' => 'required|integer|exists:roles,id',
            // Validación dinámica
            'RolId' => [
                'required',
                'integer',
                Rule::in($rolesPermitidos), // <= SOLO los roles permitidos
            ],

            'telefono' => 'required|string|max:10|regex:/^[0-9]+$/',
            'domicilio' => 'required|string|max:100',
            'localidadColonia' => 'required|string|max:100',

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

            'sexo.in' => 'El sexo debe ser M (Masculino) o F (Femenino).',

            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debe ser un correo válido.',
            'email.unique' => 'El correo ya está en uso.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no debe tener más de 10 caracteres.',
            'telefono.regex' => 'El teléfono solo debe contener números.',

            'RolId.required' => 'El rol es obligatorio.',
            'RolId.integer' => 'El rol debe ser un número válido.',
            'RolId.exists' => 'El rol seleccionado no existe.',
            'RolId.in' => 'No tienes permiso para asignar este rol.',

            'domicilio.required' => 'El domicilio es obligatorio.',
            'domicilio.max' => 'El domicilio no debe exceder los 100 caracteres.',

            'localidadColonia.required' => 'La colonia/localidad es obligatoria.',
            'localidadColonia.max' => 'La colonia/localidad no debe exceder los 100 caracteres.',

            'estatus.required' => 'El estatus es obligatorio.',
            'estatus.in' => 'El estatus debe ser Habilitado o Deshabilitado.',

            // 'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'El campo de contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
