<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmpresaRequest extends FormRequest
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
            'cnpj' => ['required', 'max:14'],
            'nome_fantasia' => ['sometimes'],
            'razao_social' => ['required'],
            'cnae.*' => ['required', 'regex:/(\(?\d{4}\)?(\-\d)?(\/\d{2}))/']
        ];
    }

    public function messages()
    {
        return [
            'cnpj.required' => 'É necessário informar o campo CNPJ',
            'cnpj.max' => 'CNPJ incorreto',
            'razao_social.required' => 'É necessário informar o campo Razão Social',
            'cnae.*.required' => 'É necessário informar a o menos um CNAE',
            'cnae.*.regex' => 'É necessário informar o CNAE em um formato valido'
        ];
    }
}
