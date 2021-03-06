<?php

namespace App\Http\Requests\Funcionarios;

use Illuminate\Foundation\Http\FormRequest;

class CreateFuncionarioRequest extends FormRequest
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
            'nome_funcionario' => ['required', 'max:100'],
            'cargo' => ['required', "regex:/[a-zA-Z]/"],
            'admissao' => ['required', 'date_format:Y-m-d'],
            'data_de_nascimento' => ['required', 'date_format:Y-m-d'],
            'setor' => ['required', 'max:100']
        ];
    }

    public function messages()
    {
        return [
            'nome_funcionario.required' => 'É necessário informar o campo nome',
            'nome_funcionario.max' => 'Este nome é muito longo',
            'cargo.required' => 'É necessário inserir o cargo do funcionário',
            'cargo.regex' => 'Dado inserido do cargo não é valido',
            'admissao.required' => 'É necessário inserir a data de admissão',
            'admissao.date_format' => 'A data de admissão não possui formato válido',
            'data_de_nascimento.required' => 'É necessário informar a data de nascimento do funcionário',
            'data_de_nascimento.date_format' => 'A data de nascimento não possui formato válido',
            'setor.required' => 'É necessário informar o setor do funcionário',
            'setor.max' => 'Nome do setor é muito longo'
        ];
    }
}
