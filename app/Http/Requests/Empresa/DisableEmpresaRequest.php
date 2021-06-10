<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class DisableEmpresaRequest extends FormRequest
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
            'cnpj' => ['required', 'max:14', 'exists:empresas,cnpj']
        ];
    }

    public function messages()
    {
        return [
            'cnpj.required' => 'É necessário informar o campo CNPJ',
            'cnpj.max' => 'CNPJ incorreto',
            'cnpj.exists' => 'CNPJ não cadastrado no banco de dados'
        ];
    }
}
