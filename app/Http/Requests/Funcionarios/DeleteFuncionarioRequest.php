<?php

namespace App\Http\Requests\Funcionarios;

use Illuminate\Foundation\Http\FormRequest;

class DeleteFuncionarioRequest extends FormRequest
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
            'funcionario' => ['exists:funcionarios,id_funcionario', 'required']
        ];
    }

    public function messages()
    {
        return [
            'funcionario.exists' => 'Funcionario não encontrado no banco de dados',
            'funcionario.required' => 'É obrigatório informar o usuário',
        ];
    }
}
