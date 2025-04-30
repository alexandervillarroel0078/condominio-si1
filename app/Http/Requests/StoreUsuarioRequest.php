<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
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
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email|max:250',
            'password' => 'required|min:8|max:20',
            'role' => 'required|exists:roles,name',
            'empleado_id' => 'nullable|exists:empleados,id',
            'residente_id' => 'nullable|exists:residentes,id',
        ];
    }

    public function attributes()
    {
        return [
            'empleado_id' => 'empleado',
            'residente_id' => 'residente',
            'role' => 'rol',
        ];
    }
}
