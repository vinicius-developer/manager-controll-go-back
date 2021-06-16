<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class ListUsuariosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa' => ['required', 'exists:empresas,id_empresa'],
        ];
    }

    public function messages()
    {
        return [
            'empresa.required' => 'É necessario informar a empresa para consulta',
            'empresa.exists' => 'Empresa não encontrada no banco de dados'
        ];
    }
}