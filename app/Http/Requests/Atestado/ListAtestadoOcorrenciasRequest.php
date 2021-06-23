<?php

namespace App\Http\Requests\Atestado;

use Illuminate\Foundation\Http\FormRequest;

class ListAtestadoOcorrenciasRequest extends FormRequest
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
            'empresa' => ['exists:empresas,id_empresa']
        ];
    }

    public function messages()
    {
        return [
            'empresa.exists' => 'Empresa informada não encontrada no banco de dados'
        ];
    }
}
