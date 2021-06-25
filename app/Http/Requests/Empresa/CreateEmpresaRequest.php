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
            'cnpj' => ['required', 'max:19'],
            'nome_fantasia' => ['sometimes'],
            'razao_social' => ['required'],
            'cnae.*' => ['required', 'regex:/(\(?\d{4}\)?(\-\d)?(\/\d{2}))/'],
            'nome' => ['required', 'max:100'],
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'confirmed'],
            'telefones_usuarios.*' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'cnpj.required' => 'É necessário informar o campo CNPJ',
            'cnpj.max' => 'CNPJ incorreto',
            'razao_social.required' => 'É necessário informar o campo Razão Social',
            'cnae.*.required' => 'É necessário informar a o menos um CNAE',
            'cnae.*.regex' => 'É necessário informar o CNAE em um formato valido',
            'nome.required' => "É necessário informar o nome",
            'nome.max' => "Nome é muito longo",
            'email.required' => "E-mail é necessário",
            'email.email' => "E-mail não está no padrão correto",
            'password.required' => 'É necessário informar o campo passoword', 
            'password.confirmed' => 'As senhas não são compátiveis',
            'telefones_usuarios.*.required' => 'É necessário informar uma telefone correto'
        ];
    }
}
