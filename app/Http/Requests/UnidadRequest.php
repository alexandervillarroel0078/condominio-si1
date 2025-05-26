<?php
// app/Http/Requests/UnidadRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnidadRequest extends FormRequest
{
    public function authorize()
    {
        // Ajusta según tu política de permisos
        $user = auth()->user();
        return $user && \Illuminate\Support\Facades\Gate::allows('manage', \App\Models\Unidad::class);
    }

    public function rules()
    {
        $unidadId = $this->route('unidad')?->id;

        return [
            'codigo'               => [
                                        'required',
                                        'string','max:50',
                                        Rule::unique('unidades','codigo')->ignore($unidadId),
                                    ],
            'placa'                => ['nullable','string','max:50'],
            'marca'                => ['nullable','string','max:100'],
            'capacidad'            => ['required','integer','min:1'],
            'estado'               => ['required','in:activa,inactiva'],
            'personas_por_unidad'  => ['required','integer','min:1'],
            'tiene_mascotas'       => ['required','boolean'],
            'vehiculos'            => ['required','integer','min:0'],
        ];
    }
}
