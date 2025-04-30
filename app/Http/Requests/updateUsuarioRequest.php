<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateUsuarioRequest extends FormRequest
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
        $user = $this->route('user');
        $userId = $user->id;
        return [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|max:250|unique:users,email,'.$userId,
            'password' => 'required|min:3|max:20',
            'empleado_id' => 'required|integer|exists:empleados,id',
            'role' => 'required|exists:roles,name'
            
        ];
        // añadir luego:
        // 'password' => 'required|min:3|max:20'
    }
}
