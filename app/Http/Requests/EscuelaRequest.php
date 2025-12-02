<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EscuelaRequest extends FormRequest
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
            'nombre_escuela'      => 'required|string|max:255',
            'direccion'           => 'required|string|max:255',
            'departamento'        => 'required|string|max:255',
            'turno'               => 'required|string|max:255',
            'nivel'               => 'required|string|max:255',
            'clave_trabajo'       => 'required|string|max:50',
            'numero_cct'          => 'required|string|max:50',
            'zona_escolar'        => 'required|string|max:50',
            'domicilio'           => 'required|string|max:255',
            'localidad_colonia'   => 'required|string|max:255',
            'municipio'           => 'required|string|max:255',
            'telefono'            => 'required|numeric|digits_between:7,15',
            'docente'             => 'required|string|max:255',
            'subdirector_escolar' => 'required|string|max:255',
            'director_escolar'    => 'required|string|max:255',
            'secretario_escolar'  => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            // nombre_escuela
            'nombre_escuela.required' => 'El nombre de la escuela es obligatorio.',
            'nombre_escuela.string'   => 'El nombre de la escuela debe ser un texto válido.',
            'nombre_escuela.max'      => 'El nombre de la escuela no puede tener más de 255 caracteres.',

            // direccion
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string'   => 'La dirección debe ser un texto válido.',
            'direccion.max'      => 'La dirección no puede tener más de 255 caracteres.',

            // departamento
            'departamento.required' => 'El departamento es obligatorio.',
            'departamento.string'   => 'El departamento debe ser un texto válido.',
            'departamento.max'      => 'El departamento no puede tener más de 255 caracteres.',

            // turno
            'turno.required' => 'El turno es obligatorio.',
            'turno.string'   => 'El turno debe ser un texto válido.',
            'turno.max'      => 'El turno no puede tener más de 255 caracteres.',

            // nivel
            'nivel.required' => 'El nivel es obligatorio.',
            'nivel.string'   => 'El nivel debe ser un texto válido.',
            'nivel.max'      => 'El nivel no puede tener más de 255 caracteres.',

            // clave_trabajo
            'clave_trabajo.required' => 'La clave de trabajo es obligatoria.',
            'clave_trabajo.string'   => 'La clave de trabajo debe ser un texto válido.',
            'clave_trabajo.max'      => 'La clave de trabajo no puede tener más de 50 caracteres.',

            // numero_cct
            'numero_cct.required' => 'El número CCT es obligatorio.',
            'numero_cct.string'   => 'El número CCT debe ser un texto válido.',
            'numero_cct.max'      => 'El número CCT no puede tener más de 50 caracteres.',

            // zona_escolar
            'zona_escolar.required' => 'La zona escolar es obligatoria.',
            'zona_escolar.string'   => 'La zona escolar debe ser un texto válido.',
            'zona_escolar.max'      => 'La zona escolar no puede tener más de 50 caracteres.',

            // domicilio
            'domicilio.required' => 'El domicilio es obligatorio.',
            'domicilio.string'   => 'El domicilio debe ser un texto válido.',
            'domicilio.max'      => 'El domicilio no puede tener más de 255 caracteres.',

            // localidad_colonia
            'localidad_colonia.required' => 'La localidad o colonia es obligatoria.',
            'localidad_colonia.string'   => 'La localidad o colonia debe ser un texto válido.',
            'localidad_colonia.max'      => 'La localidad o colonia no puede tener más de 255 caracteres.',

            // municipio
            'municipio.required' => 'El municipio es obligatorio.',
            'municipio.string'   => 'El municipio debe ser un texto válido.',
            'municipio.max'      => 'El municipio no puede tener más de 255 caracteres.',

            // telefono
            'telefono.required'       => 'El teléfono es obligatorio.',
            'telefono.numeric'        => 'El teléfono solo debe contener números.',
            'telefono.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos.',

            // docente
            'docente.required' => 'El nombre del docente es obligatorio.',
            'docente.string'   => 'El nombre del docente debe ser un texto válido.',
            'docente.max'      => 'El nombre del docente no puede tener más de 255 caracteres.',

            // subdirector_escolar
            'subdirector_escolar.required' => 'El nombre del subdirector escolar es obligatorio.',
            'subdirector_escolar.string'   => 'El nombre del subdirector escolar debe ser un texto válido.',
            'subdirector_escolar.max'      => 'El nombre del subdirector escolar no puede tener más de 255 caracteres.',

            // director_escolar
            'director_escolar.required' => 'El nombre del director escolar es obligatorio.',
            'director_escolar.string'   => 'El nombre del director escolar debe ser un texto válido.',
            'director_escolar.max'      => 'El nombre del director escolar no puede tener más de 255 caracteres.',

            // secretario_escolar
            'secretario_escolar.required' => 'El nombre del secretario escolar es obligatorio.',
            'secretario_escolar.string'   => 'El nombre del secretario escolar debe ser un texto válido.',
            'secretario_escolar.max'      => 'El nombre del secretario escolar no puede tener más de 255 caracteres.',
        ];
    }
}
