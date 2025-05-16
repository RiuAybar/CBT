<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.exists' => 'No existe una cuenta con ese correo.',
        ];
    }
}
