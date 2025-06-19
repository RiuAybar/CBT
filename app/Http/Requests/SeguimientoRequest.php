<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SeguimientoRequest extends FormRequest
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
        $Id = $this->route('Seguimiento') ?? null;

        return [
            'materia_id' => ['required', 'exists:materias,id'],
            'semestre_id' => ['required', 'exists:semestres,id'],
            'grupo_id' => ['required', 'exists:grupos,id'],
            'carrera_id' => ['required', 'exists:carreras,id'],
            'profesor_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || !$user->hasRole('profesor')) {
                        $fail('El usuario seleccionado no tiene el rol de profesor.');
                    }
                },
            ],
            'ano' => [
                'required',
                'digits:4',
                Rule::unique('seguimientos')
                    ->where(function ($query) {
                        return $query->where('materia_id', $this->materia_id)
                            ->where('semestre_id', $this->semestre_id)
                            ->where('grupo_id', $this->grupo_id)
                            ->where('carrera_id', $this->carrera_id)
                            ->where('profesor_id', $this->profesor_id);
                    })
                    ->ignore($Id),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'materia_id.required' => 'La materia es obligatoria.',
            'semestre_id.required' => 'El semestre es obligatorio.',
            'grupo_id.required' => 'El grupo es obligatorio.',
            'carrera_id.required' => 'La carrera es obligatoria.',
            'profesor_id.required' => 'El profesor es obligatorio.',
            'ano.required' => 'El año es obligatorio.',
            'ano.digits' => 'El año debe tener 4 dígitos.',
            'ano.unique' => 'Ya existe un seguimiento con los mismos datos.',
        ];
    }
}
