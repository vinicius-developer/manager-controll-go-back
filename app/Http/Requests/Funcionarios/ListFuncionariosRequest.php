<?php

namespace App\Http\Requests\Funcionarios;

use Illuminate\Foundation\Http\FormRequest;

class ListFuncionariosRequest extends FormRequest
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
        return[
            'empresa.exists' => 'Essa empresa não está cadastrada em nosso sistema'
        ];
    }
}
